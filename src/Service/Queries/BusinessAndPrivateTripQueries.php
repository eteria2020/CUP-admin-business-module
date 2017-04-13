<?php

namespace CUPAdminBusinessModule\Service\Queries;

use BusinessCore\Service\Helper\SearchCriteria;
use Doctrine\ORM\EntityManager;

class BusinessAndPrivateTripQueries
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
        $query = $this->entityManager->createQuery('SELECT COUNT(t.id) FROM \SharengoCore\Entity\Trips t ');
        return $query->getSingleScalarResult();
    }

    public function searchTrips(SearchCriteria $searchCriteria, $countFiltered = false)
    {
        $select = $countFiltered ? 'COUNT(e.id)' : 'e';
        $dql = 'SELECT ' . $select . '
        FROM \SharengoCore\Entity\Trips e
        LEFT JOIN \BusinessCore\Entity\BusinessTrip bt WITH bt.trip = e.id
        LEFT JOIN bt.business b
        LEFT JOIN e.customer cu
        LEFT JOIN cu.card cc
        LEFT JOIN e.fleet f
        LEFT JOIN e.car c
        LEFT JOIN e.tripPayment tp ';

        $query = $this->entityManager->createQuery();

        $where = false;

        $searchColumnNull = $searchCriteria->getSearchColumnNull();
        $searchColumn = $searchCriteria->getSearchColumn();
        $searchValue = $searchCriteria->getSearchValue();
        if (!empty($searchColumnNull)) {
            $dql .= 'WHERE ' . $searchColumnNull . ' IS NULL ';
            $where = true;
        } else if (!empty($searchColumn) && !empty($searchValue)) {
            switch($searchColumn) {
                case 'bt.business':
                    //if I'm searching by business code i have to get the Identity of the property business of the entity BusinessTrip
                    $searchColumn = 'IDENTITY('.$searchColumn.')';
                    //intentional fall through
                case 'e.id':
                case 'cu.id':
                    $dql .= 'WHERE ' . $searchColumn . ' = :value ';
                    break;
                default:
                    $searchColumn = 'LOWER(' . $searchColumn . ')';
                    $searchValue = strtolower("%" . $searchValue . "%");
                    $dql .= 'WHERE ' . $searchColumn . ' LIKE :value ';
            }

            $query->setParameter('value', $searchValue);
            $where = true;
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
            $dql .= ($where?' AND ' : ' WHERE ') . $columnFromDate . ' >= :from ';
            $dql .= ' AND ' . $columnToDate . ' <= :to ';
            $query->setParameter('from', $fromDate . ' 00:00:00');
            $query->setParameter('to', $toDate . ' 23:59:59');
        }

        if (!$countFiltered) {
            $sortColumn = $searchCriteria->getSortColumn();
            $sortOrder = $searchCriteria->getSortOrder();
            if (!empty($sortColumn) && !empty($sortOrder)) {
                $dql .= 'ORDER BY ' . $sortColumn . ' ' . $sortOrder . ' ';
            }

            $paginationLength = $searchCriteria->getPaginationLength();
            $paginationStart = $searchCriteria->getPaginationStart();
            if ($paginationLength > 0 && !is_nan($paginationStart)) {
                $query->setMaxResults($paginationLength);
                $query->setFirstResult($paginationStart);
            }
        }

        $query->setDql($dql);
        if ($countFiltered) {
            return $query->getSingleScalarResult();
        }

        return $query->getResult();
    }
}
