CREATE SCHEMA businesses
       AUTHORIZATION postgres;
GRANT ALL ON SCHEMA businesses TO public;

-- Table: businesses.business

-- DROP TABLE businesses.business;
-- creare / modificare un'azienda. Campi da prevedere: nome azienda,
-- codice azienda (univoco - 6 caratteri alfanumerici, generati random dal sistema),
-- lista domini aziendali, dati di fatturazione
-- (ragione sociale, indirizzo, cap, città, provincia, partita IVA)
-- visualizzare la scheda dell'azienda: predisporre un layout a tab come per clienti, auto, ecc.


CREATE TABLE businesses.business
(
  id serial PRIMARY KEY,
  name text,
  code text,
  domains jsonb,
  address text,
  zip_code text,
  province text,
  city text,
  vat text,
  email text,
  phone text,
  fax text,
  inserted_ts timestamp with time zone DEFAULT now(),
  update_ts timestamp with time zone
);

ALTER TABLE businesses.business
  OWNER TO sharengo;