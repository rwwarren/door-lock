--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: doorlock; Type: SCHEMA; Schema: -; Owner: ryan
--

-- CREATE SCHEMA doorlock;


-- ALTER SCHEMA doorlock OWNER TO ryan;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


--
-- Name: pgcrypto; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS pgcrypto WITH SCHEMA public;


--
-- Name: EXTENSION pgcrypto; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION pgcrypto IS 'cryptographic functions';


--
-- Name: uuid-ossp; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS "uuid-ossp" WITH SCHEMA public;


--
-- Name: EXTENSION "uuid-ossp"; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION "uuid-ossp" IS 'generate universally unique identifiers (UUIDs)';


SET search_path = doorlock, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: reseturls; Type: TABLE; Schema: doorlock; Owner: ryan; Tablespace: 
--

CREATE TABLE reseturls (
    id integer NOT NULL,
    user_id integer NOT NULL,
    reset_url character(255) NOT NULL,
    expiration timestamp with time zone DEFAULT (now() + '1 day'::interval),
    is_valid boolean DEFAULT true NOT NULL
);


ALTER TABLE reseturls OWNER TO ryan;

--
-- Name: reseturls_id_seq; Type: SEQUENCE; Schema: doorlock; Owner: ryan
--

CREATE SEQUENCE reseturls_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE reseturls_id_seq OWNER TO ryan;

--
-- Name: reseturls_id_seq; Type: SEQUENCE OWNED BY; Schema: doorlock; Owner: ryan
--

ALTER SEQUENCE reseturls_id_seq OWNED BY reseturls.id;


SET search_path = public, pg_catalog;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: ryan
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE users_id_seq OWNER TO ryan;

SET search_path = doorlock, pg_catalog;

--
-- Name: users; Type: TABLE; Schema: doorlock; Owner: ryan; Tablespace: 
--

CREATE TABLE users (
    id integer DEFAULT nextval('public.users_id_seq'::regclass) NOT NULL,
    name character(255) NOT NULL,
    username character(255) NOT NULL,
    password bytea NOT NULL,
    email character(255) NOT NULL,
    authy_id integer,
    card_id character(255),
    is_admin boolean DEFAULT false NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    date_joined timestamp with time zone DEFAULT now() NOT NULL,
    user_uuid uuid DEFAULT public.gen_random_uuid() NOT NULL
);


ALTER TABLE users OWNER TO ryan;

--
-- Name: id; Type: DEFAULT; Schema: doorlock; Owner: ryan
--

ALTER TABLE ONLY reseturls ALTER COLUMN id SET DEFAULT nextval('reseturls_id_seq'::regclass);


--
-- Data for Name: reseturls; Type: TABLE DATA; Schema: doorlock; Owner: ryan
--

-- COPY reseturls (id, user_id, reset_url, expiration, is_valid) FROM stdin;
-- \.


--
-- Name: reseturls_id_seq; Type: SEQUENCE SET; Schema: doorlock; Owner: ryan
--

SELECT pg_catalog.setval('reseturls_id_seq', 1, false);


--
-- Data for Name: users; Type: TABLE DATA; Schema: doorlock; Owner: ryan
--

-- COPY users (id, name, username, password, email, authy_id, card_id, is_admin, is_active, date_joined, user_uuid) FROM stdin;
-- 2	test                                                                                                                                                                                                                                                           	test2                                                                                                                                                                                                                                                          	\\x61736466	asdf                                                                                                                                                                                                                                                           	\N	\N	t	t	2015-10-14 20:24:38.880747-07	69e0f31b-0a12-4b1a-9111-fe366f227f34
-- 1	test                                                                                                                                                                                                                                                           	asdf                                                                                                                                                                                                                                                           	\\x61736466	asdf                                                                                                                                                                                                                                                           	\N	\N	t	f	2015-10-18 22:20:41.477816-07	be2af6ca-b2a6-4fed-b572-8c693d001464
-- 3	test                                                                                                                                                                                                                                                           	test                                                                                                                                                                                                                                                           	\\xb1afae59d1646482d11fe9ba27ed5a054f37eddb926138bdfe7fae4b2529a4c5	asdf                                                                                                                                                                                                                                                           	\N	\N	t	t	2015-10-15 00:30:50.279236-07	249c61eb-3b76-49ed-a2a7-8e70aa3cc7d9
-- \.


SET search_path = public, pg_catalog;

--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: ryan
--

SELECT pg_catalog.setval('users_id_seq', 3, true);


SET search_path = doorlock, pg_catalog;

--
-- Name: reseturls_pkey; Type: CONSTRAINT; Schema: doorlock; Owner: ryan; Tablespace: 
--

ALTER TABLE ONLY reseturls
    ADD CONSTRAINT reseturls_pkey PRIMARY KEY (id);


--
-- Name: users_pkey; Type: CONSTRAINT; Schema: doorlock; Owner: ryan; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: fk_user_id; Type: FK CONSTRAINT; Schema: doorlock; Owner: ryan
--

ALTER TABLE ONLY reseturls
    ADD CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES users(id);


--
-- Name: doorlock; Type: ACL; Schema: -; Owner: ryan
--

REVOKE ALL ON SCHEMA doorlock FROM PUBLIC;
REVOKE ALL ON SCHEMA doorlock FROM ryan;
GRANT ALL ON SCHEMA doorlock TO ryan;
GRANT ALL ON SCHEMA doorlock TO read;


--
-- Name: public; Type: ACL; Schema: -; Owner: ryan
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM ryan;
GRANT ALL ON SCHEMA public TO ryan;
GRANT ALL ON SCHEMA public TO PUBLIC;
GRANT USAGE ON SCHEMA public TO read;


--
-- Name: users; Type: ACL; Schema: doorlock; Owner: ryan
--

REVOKE ALL ON TABLE users FROM PUBLIC;
REVOKE ALL ON TABLE users FROM ryan;
GRANT ALL ON TABLE users TO ryan;
GRANT SELECT ON TABLE users TO read;


--
-- PostgreSQL database dump complete
--

