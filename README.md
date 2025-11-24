# Laravel Developer Test

This test is designed to get an understanding of your abilities as a developer within the Laravel Framework. This
is to be completed on your own time without any supervision.

You are expected to utilize agentic coding tools, and document your usage. If you are performing this test based on
a request from one of our team members and you require an API token for an agentic coding platform, you may request
it from your contact at Victory.

## Technical Requirements

- **PHP**: 8.1 or higher
- **Laravel**: 12.x
- **Database**: MySQL, PostgreSQL, or SQLite (your choice)
- **Composer**: Latest stable version
- **Node.js**: 18.x or higher (for asset compilation)

## Assignment

Start by forking this repository and cloning it to your local environment, when complete you will create a PR back
to the original repo. This repo is basically a vanilla Laravel framework with no other code committed in. You can
run the development environment in whatever way suits you the best, and the work can be completed locally.

Create a simple application that will pull data from a public API, store it in a relational database, and display the data.
You can be as creative as you like with the interface and show as much or as little data as you like. Use a public data
API with good documentation, for example you could show a list of current Congressmen using data from
https://api.congress.gov/. This test is primarily about data handling, API use and agentic coding tools - a well
styled front end is good for bonus points but not required.

## Timing

This task should take you between 2 and 4 hours of heads down coding work, if you feel like it will take significantly
longer please reach out to your contact for clarification.

## Structure

The idea is to see how you think, so you may structure the code in any way you see fit - we will be looking to understand
how you broke out the structure between:
- Reading the source data
- Storing the data locally
- Accessing the data via your own front end
- Re-Reading and updating the data

## Expectations

These are some general expectations for the project, this is the sort of thing that would be laid out in tickets.
- Use idempotent Laravel jobs to acquire the data
- Separate the data handling logic from the execution mechanisms
- Create an artisan command that can run the same tasks with visible outputs for testing
- Create a schedule task to update the data on a set schedule
- Create tests for any services that you build

## Deliverables

Your submission should include:
- **Models and Migrations**: Database schema to store the API data
- **Service Classes**: Clean, testable code for API interactions
- **Laravel Jobs**: Queue-able jobs for data fetching and processing
- **Artisan Command**: Command to manually trigger data import
- **Scheduled Task**: Configuration in `app/Console/Kernel.php` for automatic updates
- **Routes and Controllers**: Basic web interface to display the data
- **Tests**: Unit/Feature tests for your service classes
- **Documentation**: `AGENTIC_CODING_NOTES.md` file (see below)
- **Getting Started Instructions**: Update this README with setup instructions (see template below)

## Agentic Coding Tools

The ability to use agentic coding tools as part of your workflow is very important to this role and a core part
of this test. We recognize that these tools are new and we are happy to train on their usage, but we need to see
some usage to evaluate your capabilities. Ultimately you must produce good, clean, maintainable and efficient code -
agents should be a sous chef, not a head chef, and you must maintain responsibility for the finished product.

Create a file named `AGENTIC_CODING_NOTES.md` in the root directory with at least one well thought out prompt which
you fed into an agentic tool - such as Claude Code or OpenAI's Codex - and describe the quality of the work produced
by the agent. Here is what we are looking for:
- How did you describe the project to the coding agent (your prompt)?
- Which models and options did you use and why?
- How much did you interact with the agent during its coding process?
- Describe the quality of the code created and what process you went through to evaluate the code.
- Describe the changes you made to the agentically created code.

## Getting Started

This application is a Star Wars Data Hub built with Laravel 12 that integrates with SWAPI (Star Wars API) to fetch, store, and display information about characters and planets.

### Prerequisites
- PHP 8.2 or higher
- Composer (latest stable version)
- Node.js 18.x or higher & npm
- SQLite (included with PHP) or MySQL/PostgreSQL

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/Alemoretti/LaravelDeveloperTest.git
   cd LaravelDeveloperTest
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Set up environment**
   ```bash
   cp .env.example .env
   ```
   
   The `.env` file is already configured for SQLite:
   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=database/database.sqlite
   ```

4. **Create the database file**
   ```bash
   touch database/database.sqlite
   ```

5. **Generate application key and run migrations**
   ```bash
   php artisan key:generate
   php artisan migrate
   ```

6. **Build frontend assets**
   ```bash
   npm run build
   ```

### Running the Application

1. **Start the development server**
   ```bash
   php artisan serve
   ```

2. **Start the queue worker** (in a separate terminal)
   ```bash
   php artisan queue:work
   ```

3. **Access the application**
   - Open: `http://localhost:8000`
   - You'll be redirected to the Characters page

### Testing the Data Import

**Important**: Sync planets before characters to ensure homeworld relationships are established.

**Option 1: Sync all resources**
```bash
php artisan swapi:sync
```

**Option 2: Sync specific resources**
```bash
php artisan swapi:sync --resource=planets
php artisan swapi:sync --resource=people
```

**View the imported data:**
- Characters: `http://localhost:8000/characters`
- Planets: `http://localhost:8000/planets`

**The scheduled task runs:** Daily at 2:00 AM (configured in `routes/console.php`)

To test the scheduler locally:
```bash
php artisan schedule:work
```

### Running Tests

Run the complete test suite (40 tests, 91 assertions):
```bash
php artisan test
```

Run specific test suites:
```bash
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature
```

Check code style compliance (PSR-12):
```bash
./vendor/bin/pint --test
```

### Key Features

- **Search & Filter**: Search characters by name/gender/birth year, planets by name/climate/terrain
- **Relationships**: Characters linked to their homeworld planets
- **Pagination**: 15 items per page for listings, 10 for planet residents
- **Dark Mode**: Full dark mode support throughout the UI
- **Responsive**: Mobile-friendly design with Tailwind CSS 4.0

### Troubleshooting

**Queue jobs not processing?**
- Ensure `php artisan queue:work` is running

**No data after sync?**
- Check SWAPI accessibility: `curl https://swapi.dev/api/people/1/`
- Review logs: `storage/logs/laravel.log`

**Frontend styles not loading?**
- Run `npm run build` or `npm run dev`

## Evaluation Criteria

The people evaluating your submission will do the following before we schedule the final interview.

On a global level, we are evaluating you on your ability
- to communicate in writing (both code and general documentation)
- to infer assumptions in the tasks
- to prioritize tasks and effectively communicate why during the interview.

We will do the following:
1. Clone and run your fork following the instructions in the README under "Getting Started". We are simply checking to ensure the application functions as expected.
    - We expect there to be instructions for testing the data import and viewing the displayed data (remember that we prize solid communication)
2. Read your PR
    - Be sure you have a good commit message that describes why the work is done like it is (not what was done, we can see that in the code)
        - this is a chance to show off written communication skills
    - Be sure your code has passed all checks in the build
3. Attempt to run your PR to show that the code is in the expected functional state
4. Attempt to run the test suite
5. (Optional) Read any Github issues you created to track your progress - issue tracking is not required but demonstrates good project management practices
