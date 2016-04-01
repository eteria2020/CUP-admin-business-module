CREATE SCHEMA businesses
       AUTHORIZATION postgres;
GRANT ALL ON SCHEMA businesses TO public;

CREATE TABLE businesses.business
(
  code text PRIMARY KEY,
  name text,
  domains jsonb,
  address text,
  zip_code text,
  province text,
  city text,
  vat_number text,
  email text,
  phone text,
  fax text,
  payment_frequence text,
  payment_type text,
  business_mail_control bool,
  inserted_ts timestamp with time zone DEFAULT now(),
  updated_ts timestamp with time zone
);

ALTER TABLE businesses.business
  OWNER TO sharengo;