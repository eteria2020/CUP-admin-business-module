<?php

namespace CUPAdminBusinessModule\Service\Queries;

use BusinessCore\Service\Helper\SearchCriteria;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;

class BusinessAndPrivateInvoiceQueries
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * BusinessAndPrivateTripRepository constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function countAll()
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('tot', 'tot');

        $sql = 'select COUNT(sub.id) as tot from (
                select id from business.business_invoice
                union all
                select id from invoices) sub';

        $query = $this->entityManager->createNativeQuery($sql, $rsm);

        return $query->getSingleScalarResult();
    }

    public function searchInvoices(SearchCriteria $searchCriteria, $countFiltered = false)
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('invoice_number', 'invoice_number');
        $select = 'sub.*';
        if ($countFiltered) {
            $select = 'COUNT(sub.*) as tot';
            $rsm->addScalarResult('tot', 'tot');
        } else {
            $rsm->addScalarResult('id', 'id');
            $rsm->addScalarResult('name', 'name');
            $rsm->addScalarResult('fleet_id', 'fleet_id');
            $rsm->addScalarResult('date', 'date');
            $rsm->addScalarResult('type', 'type');
            $rsm->addScalarResult('total', 'total');
            $rsm->addScalarResult('is_business', 'is_business');
        }

        $sql = 'select ' . $select . ' from (
            select bi.id, bi.fleet_id, bi.invoice_number, b.name as name, bi.invoice_date as date, bi.type, bi.amount as total, true as is_business, f.name as fleet_name from business.business_invoice as bi
            left join business.business as b on (b.code = bi.business_code)
            inner join fleets as f on (f.id = bi.fleet_id)
            union all
            select pi.id, pi.fleet_id, pi.invoice_number, concat_ws(\' \', c.surname::text, c.name::text) AS name, pi.invoice_date as date, pi.type::text, pi.amount as total, false as is_business, f.name as fleet_name from invoices as pi
            left join customers as c on (c.id = pi.customer_id)
            inner join fleets as f on (f.id = pi.fleet_id)
            ) sub WHERE 1 = 1 ';

        $query = $this->entityManager->createNativeQuery($sql, $rsm);

        $searchColumn = $searchCriteria->getSearchColumn();
        $searchValue = $searchCriteria->getSearchValue();
        if (!empty($searchColumn) && !empty($searchValue)) {
            $likeValue = strtolower("%" . $searchValue . "%");
            if($searchColumn==="customer_name"){
                $sql .= 'AND LOWER(name) LIKE :value AND is_business=false ';
            } else if($searchColumn==="business_name"){
                $sql .= 'AND LOWER(name) LIKE :value AND is_business=true ';
            } else {
                $sql .= 'AND LOWER(' . $searchColumn . ') LIKE :value ';
            }
            $query->setParameter('value', $likeValue);

        }

        $fromDate = $searchCriteria->getFromDate();
        $toDate = $searchCriteria->getToDate();
        $columnFromDate = $searchCriteria->getColumnFromDate();
        $columnToDate = $searchCriteria->getColumnToDate();
        if (!empty($fromDate) &&
            !empty($toDate) &&
            !empty($columnFromDate) &&
            !empty($columnToDate)
        ) {
            $sql .= ' AND ' . $columnFromDate . ' >= :from ';
            $sql .= ' AND ' . $columnToDate . ' <= :to ';
            $query->setParameter('from', $fromDate . ' 00:00:00');
            $query->setParameter('to', $toDate . ' 23:59:59');
        }

        if (!$countFiltered) {
            $sortColumn = $searchCriteria->getSortColumn();
            $sortOrder = $searchCriteria->getSortOrder();
            if (!empty($sortColumn) && !empty($sortOrder)) {
                $sql .= 'ORDER BY ' . $sortColumn . ' ' . $sortOrder . ' ';
            }

            $paginationLength = $searchCriteria->getPaginationLength();
            $paginationStart = $searchCriteria->getPaginationStart();

            if (!empty($paginationLength) && !is_nan($paginationStart)) {
                $sql .= 'LIMIT ' . $paginationLength;
                $sql .= ' OFFSET ' . $paginationStart;
            }
        }
        $query->setSQL($sql);


        if ($countFiltered) {
            return $query->getSingleScalarResult();
        }
        return $query->getResult();
    }
}
