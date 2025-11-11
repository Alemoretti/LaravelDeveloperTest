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

## Getting Started (To Be Completed by Candidate)

When you complete your submission, replace this section with instructions for setting up and running your project.
Your instructions should include:

```markdown
## Getting Started

### Prerequisites
- PHP 8.x
- Composer
- Node.js & npm
- [Database of your choice]

### Installation
1. Clone the repository
2. Copy `.env.example` to `.env`
3. [Your specific setup steps]
4. Run migrations
5. [Any seeding or initial data setup]

### Running the Application
- Start the development server: [command]
- Access the application at: [URL]

### Testing the Data Import
- Run the artisan command: `php artisan [your-command]`
- View the imported data at: [URL]
- The scheduled task runs: [frequency]

### Running Tests
- Run the test suite: `php artisan test`
```

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
