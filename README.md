
# Assignment

Greetings,

Here are the steps to run the project:

```
git clone git@github.com:ocricci/timberhub.git

cd timberhub

docker compose up
```

It is going to run composer install too, so it'll take a while to start

The expected log of the timberhub_php container is:

```
...
timberhub_php    | [17-Jun-2024 13:49:27] NOTICE: fpm is running, pid 750
timberhub_php    | [17-Jun-2024 13:49:27] NOTICE: ready to handle connections
```

( if you don't get to this state, please let me know ! )

---

the next step is to boot the database ( migrate and seed )
to do this, you can call 

curl http://127.0.0.1/boot

--

nginx is mapped to receive requests from timberhub.local ( or 127.0.0.1:80 )

this step is not necessay, but you can edit /etc/hosts to map it and then access as:

curl http://timberhub.local/boot

---

the boot step creates the sqlite tables and seeds them with test data, the output is:

"SQLite3 booted"

---

Now you are ready to check the code and evaluate the test, here are the endpoints available:

GET /boot

GET /suppliers

GET /suppliers/{id}

GET /products

GET /products/{id}

GET /suppliers/{id}/products

GET /suppliers/{id}/products/{id}

POST /products

POST /suppliers/{id}/products/{id}

---

About the project
-----------------

This project uses the Slim framework 4 and SQLite3, the decision was made because I wanted it to be simple and not rely so much in the framework nor ORM

I think this project is capable of showing how I would work with OOP ( despite the ugly queries being made in the repos, I had fun coding it - A long time without using prepares and stmts )

To make it clear, I am fully aware and have been working with Eloquent and Doctrine, the choice for SQLite is to allow me in the future migrate to Turso DB ( which I am curious about - found the PHP integration to be quite new )

