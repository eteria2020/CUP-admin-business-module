CREATE SCHEMA businesses
       AUTHORIZATION postgres;
GRANT ALL ON SCHEMA businesses TO public;

CREATE TYPE business_payment_type AS ENUM ('Bonifico', 'Carta di credito');
CREATE TYPE business_payment_frequence AS ENUM ('Settimanale', 'Quindicinale', 'Mensile');

CREATE TABLE businesses.business
(
  code text PRIMARY KEY,
  name text,
  domains text[],
  address text,
  zip_code text,
  province text,
  city text,
  vat_number text,
  email text,
  phone text,
  fax text,
  payment_frequence business_payment_frequence,
  payment_type business_payment_type,
  business_mail_control bool,
  inserted_ts timestamp with time zone DEFAULT now(),
  updated_ts timestamp with time zone
);

ALTER TABLE businesses.business
  OWNER TO sharengo;