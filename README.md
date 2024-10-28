# NFQ-Shop

## Prerequisites

To set up and run this project, ensure the following tools are installed on your system:

- **Docker**: Docker is required to create and manage containers for running the application.
  - [Download Docker](https://docs.docker.com/get-docker/)

- **Docker Compose**: Docker Compose is used to define and run multi-container Docker applications.
  - Docker Desktop includes Docker Compose by default, but you can also install it separately if needed.
  - [Download Docker Compose](https://docs.docker.com/compose/install/)

Ensure both are installed and running before proceeding with the setup instructions.


## Setup Instructions

Follow these steps to set up the project. Each command includes a brief description of its purpose.

1. **Clone the repository:**

    Clone the project repository to your local machine.

    ```sh
    git clone https://github.com/anxic/nfq-shop.git
    cd nfq-shop
    ```

2. **Build Docker images:**

    Build the Docker images specified in the `docker-compose.yml` file. This step creates isolated environments for the application, database, and other services.

    ```sh
    docker compose build
    ```

3. **Start the containers:**

    Start the Docker containers in detached mode (`-d`), which runs them in the background. This initializes the environment with all necessary services.

    ```sh
    docker compose up -d
    ```

4. **Install PHP dependencies:**

    Use Composer to install the project’s PHP dependencies, specified in `composer.json`. This installs all necessary libraries and tools for the project.

    ```sh
    docker compose run --rm app composer install
    ```

5. **Run database migrations:**

    Execute the Doctrine migrations to apply any database schema changes. This ensures that the database structure is correctly set up.

    ```sh
    docker compose run --rm app php bin/console doctrine:migrations:migrate
    ```

6. **Load sample data (fixtures):**

    Load fixture data into the database. Fixtures provide pre-defined data samples, useful for initializing a working environment with example data.

    ```sh
    docker compose run --rm app php bin/console doctrine:fixtures:load
    ```

7. **Generate JWT keys:**

    Generate JWT (JSON Web Token) private and public keys, which are required for secure API authentication. The `--overwrite` flag replaces any existing keys, useful for fresh setups.

    ```sh
    docker compose run --rm app php bin/console lexik:jwt:generate-keypair --overwrite
    ```

    **Note**: If you update the `JWT_PASSPHRASE` environment variable, you must regenerate the JWT key files to ensure they align with the new passphrase. This can be done by re-running the command above.


8. **Regenerate user password hash (if needed):**

    Hash a password to be used in `security.yaml`. This step allows you to secure specific accounts with hashed passwords, which are then stored in the configuration file.

    ```sh
    docker compose run --rm app php bin/console security:hash-password
    ```

    After running the command, copy the generated hashed password and add it to the `security.yaml` file. This file defines user credentials for authentication.

    Example configuration in `security.yaml`:

    ```yaml
    # config/packages/security.yaml

    providers:
        users_in_memory:
            memory:
                users:
                    user@wolfshop.fr:
                        password: 'HASHED_PASSWORD' # Use your generated password hash
                        roles: 'ROLE_API_USER'
    ```

This setup ensures your application is properly configured and secure, with hashed passwords and environment setup handled through Docker.

## Environment file
To configure the application, you need to create a `.env.local` file in the root directory and define the following environment variables:

```plaintext
APP_SECRET=
JWT_PASSPHRASE=
CLOUDINARY_CLOUD_NAME=
CLOUDINARY_API_KEY=
CLOUDINARY_API_SECRET=
```

## Commands

The application includes custom commands to manage item data within the shop. Below is a description of each command:

### Import Items Command

This command imports product data from an external REST API. Running this command will fetch items from the following endpoint:
- **API URL**: https://api.restful-api.dev/objects

You can execute this command as follows:

```sh
docker compose run --rm app php bin/console wolfshop:import-items
```

### Update Items Command
This command updates the sellIn and quality attributes for all items, ensuring data accuracy based on business rules. It is recommended to set this command as a daily cron job to maintain regular updates.

To run the command manually:
```sh
docker compose run --rm app php bin/console wolfshop:update-items
```

## Postman Collections

In the `docs/` folder, you can find Postman collections and environment configuration files to test and interact with the API. Below is an overview of each file:

- **Authentication.postman_collection**: Contains the endpoint for authenticating users and retrieving the token needed for authorized requests.
  
- **NFQShop.postman_collection**: Includes 5 endpoints for managing items in the shop:
  - List all items
  - Find an item by name
  - Find an item by ID
  - Upload a picture for an item
  - Delete a picture for a specific item

- **Local.postman_environment**: Contains all necessary variables for the local environment, including the API base URL and other required parameters.

### Usage Instructions

1. **Get the Token**: Before using the `NFQShop` collection, authenticate the user by running the endpoint in `Authentication.postman_collection` to obtain a token. This token should be saved as an environment variable in Postman.
   
2. **Token Validity**: The token generated is valid for 1 hour. Make sure to regenerate it if needed during longer sessions.

These collections and environment settings will help streamline your API testing and ensure secure access to protected endpoints.

## Dependencies

### Main Packages

- **[cloudinary/cloudinary_php](https://github.com/cloudinary/cloudinary_php)**: Manages media (image and video) uploads, transformations, and delivery through Cloudinary.
- **[doctrine/doctrine-migrations-bundle](https://github.com/doctrine/DoctrineMigrationsBundle)**: Handles database schema migrations in Symfony applications.
- **[doctrine/orm](https://github.com/doctrine/orm)**: Object-Relational Mapper (ORM) for handling database interactions through Doctrine.
- **[gedmo/doctrine-extensions](https://github.com/doctrine-extensions/DoctrineExtensions)**: Adds advanced features (like timestampable, sluggable) to Doctrine entities.
- **[lexik/jwt-authentication-bundle](https://github.com/lexik/LexikJWTAuthenticationBundle)**: Implements JWT authentication for secure API authorization.
- **[stof/doctrine-extensions-bundle](https://github.com/stof/StofDoctrineExtensionsBundle)**: Integrates Doctrine extensions, like sortable or loggable, within Symfony.
- **[symfony/console](https://github.com/symfony/console)**: Provides command-line interface (CLI) support for Symfony applications.
- **[symfony/dotenv](https://github.com/symfony/dotenv)**: Loads environment variables from a `.env` file into Symfony.
- **[symfony/framework-bundle](https://github.com/symfony/framework-bundle)**: The core of a Symfony application, integrating essential components.
- **[symfony/http-client](https://github.com/symfony/http-client)**: HTTP client for making external API requests.
- **[symfony/serializer](https://github.com/symfony/serializer)**: Serializes and deserializes data, essential for APIs and data transformation.
- **[symfony/validator](https://github.com/symfony/validator)**: Provides data validation support.

### Development Packages

- **[doctrine/doctrine-fixtures-bundle](https://github.com/doctrine/DoctrineFixturesBundle)**: Helps load and manage test data fixtures.
- **[phpstan/phpstan](https://github.com/phpstan/phpstan)**: Static analysis tool to detect errors in code.
- **[phpunit/phpunit](https://github.com/sebastianbergmann/phpunit)**: Unit testing framework for PHP applications.
- **[symfony/maker-bundle](https://github.com/symfony/maker-bundle)**: Generates boilerplate code for Symfony components.
- **[symplify/easy-coding-standard](https://github.com/symplify/easy-coding-standard)**: Ensures consistent code style.
