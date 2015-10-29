INSERT INTO doorlock.users (id, name, username, password, email, authy_id, card_id, is_admin, is_active, user_uuid)
VALUES
  (1, 'testing', 'test', '\xc871ea8634807419581ab77f0bbe19b55a955974045d42f9fc183f50a5c5cf96', 'asdf@s.com', 12, 21,
   TRUE, TRUE, '249c61eb-3b76-49ed-a2a7-8e70aa3cc7d9'),
  (2, 'updatecurrentuser', 'updatecurrentusername',
   '\xe3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855', 'asdf@c.com', 14, 24, TRUE, TRUE,
   '249a61eb-3b76-49ed-a2b7-8e70aa3cc7d9'),
  (3, 'updateuser', 'updateuser', '', 'asdf@c.com', 13, 22, TRUE, TRUE, '249c61eb-3b76-49ed-a2b7-8e70aa3cc7d9');
-- VALUES ('test', 'Test', '', 'asdf@s.com', 12, 21, TRUE, TRUE)

INSERT INTO doorlock.reseturls (id, user_id, reset_url, expiration, is_valid)
VALUES (1, 1, 'reset-url-first', now() + INTERVAL '1 day', TRUE),
  (2, 2, 'reset-url-second', now() + INTERVAL '1 day', TRUE);

