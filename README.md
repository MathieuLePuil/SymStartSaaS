<h1 align="center">SymStartSaas</h1>

<br>

## Installation

The only way to install SymStartSaaS is via composer, so you need to have it on your machine in order to proceed with the installation.

1. Create the project via the terminal
```bash
composer create-project mathieulepuil/symstartsaas
```

2. Execute the commands displayed at the end of the installation process
3. Modify the .env to link your database
4. Create the database
```bash
php bin/console d:d:c
```
5. Update the database schema
```bash
php bin/console d:s:u -f
```
<br>
Your project is now installed, you can create your account and log in.

<hr>

## Authentication

SymStartSaaS offers 5 authentication modes. By Email, Discord, Google, Github and Gitlab. 

- Email : `src/Controller/SecurityController.php` <br>
- Discord : `src/Controller/DiscordController.php` <br>
- Google : `src/Controller/GoogleController.php` <br>
- Github : `src/Controller/GithubController.php` <br>
- Gitlab : `src/Controller/GitlabController.php` <br>

You can add an OAuth authentication mode via this module: [KnpUOAuth2ClientBundle](https://github.com/knpuniversity/oauth2-client-bundle)

You can also delete them by following this procedure:

- Delete the controller.
- Delete the configuration lines in `config/packages/knpu_oauth2_client.yaml.
- Delete application data in .env (or .env.local)

Don't forget to connect your applications by entering their ids in the .env file.

<hr>

## Stripe

SymStartSaaS uses Stripe to manage subscriptions. You need to create an account on the Stripe website and retrieve your API keys into .env.

<hr>

Credits: [Mathieu Le Puil](https://github.com/MathieuLePuil)
