# Agentic Coding Notes - Star Wars Data Hub

## Summary

I provided the AI agent (Claude Sonnet 3.5/4 via Cursor) with the context of the project that I would build. I chose a Star Wars public API and narrowed down to show only characters and planets to minimize the project scope. I used fast agent for some simple tasks like setting up, creating migrations or documenting and used the Sonnet 4.5 for more complex tasks.

I divided the project in 9 steps, starting with the simple parts like setting up the environment and database. I detailed the coding practices that I wanted, like PSR, DRY, applying SOLID principles when applicable, separating a service layer to deal with the data, use enums, caching, repository pattern, DTOs, create the tests and commands. For the views (to build and review them faster), 
I chose to use Blade templates with Tailwind, without React or Vue.js.

For each feature branch/step I asked to let me review what was being built so I could test. Only after my changes and tests passed, I pushed to my repository and created the Pull Request for each step. I'll list the steps below:

**Steps that I created**
I created an additional Pull Request to add this agentic code markdown file, but these were the main steps that I asked the agent:
1. Database structure and models
2. Service classes
3. Laravel jobs
4. Artisan command
5. Scheduled task
6. Controllers and routes
7. Blade views
8. Tests
9. Documentation

**In summary, I asked for these details about the project requirements in the prompts:**
- Build a Laravel 12 application integrating with SWAPI (Star Wars API).
- Focus on characters and planets data
- Implement idempotent Laravel jobs for data synchronization
- I separated business logic into service classes (Service layer: SwapiService, DataSyncService, RelationshipMapper)
- Create Artisan command for manual sync
- Configure scheduled tasks for automatic updates
- Use Tailwind CSS 4.0 to make the UI better in the blade templates
- I asked for the tests with some details, but it was the part that I had the most work to fix
- From the beginning I asked in the steps to follow the best practices, Laravel conventions and PSR-12
- The dark mode was suggested by the agent so I kept it
- RESTful controllers with search, filter, and pagination
- Queue-based job processing with idempotency checks


**Some examples of interventions that I had to make**

- **PSR-12 Compliance:** Requested the agent run `./vendor/bin/pint` after each major step to ensure code follows Laravel coding standards
- **Factory Data Generation:** Asked agent to use factories instead of hardcoded test data for better test maintainability
- **Type Corrections:** Fixed tests that incorrectly expected `int` types when database stored `string` values (e.g., population fields)
- **Factory Unique Constraints:** Modified `PlanetFactory` and `CharacterFactory` to avoid using `unique()` on `swapi_id`, which caused overflow errors in tests with 20+ records. Changed to static incrementing IDs instead
- **Sort Test Logic:** Corrected character sort test to use `assertSeeInOrder()` instead of manual string position checking for more reliable assertions
- **Redirect Handling:** Updated `ExampleTest` to expect redirect (302) instead of direct response (200) after homepage behavior changed
- **UI:** Modified the ui to use modern design with tabs, cards, filter inputs and I had to remove some unecessary parts like the profile picture that was added automatically
- **Residents sync failure:** The mapper created wasn't working properly, so all the planets were showing 0 population, despite having synced data. It was necessary to inject `RelationshipMapper` into `DataSyncService` constructor, add call to `mapCharacterHomeworld()` after syncing each character and change from instantiating new `SwapiService` to using injected dependency

**Some architecture changes needed:**
- Ensured proper sync order (planets before characters) for relationship integrity
- Implemented comprehensive error handling
- Added logging
- Added idempotency checks via sync_logs table


**Some test changes needed:**
- **Test data generation:** Initial factories used `unique()` which caused test failures at scale
- **Type assumptions:** Some tests assumed integer types where strings were used in database
- **Console testing:** Command tests had framework compatibility issues and were removed, like the SyncSwapiDataCommandTest
- **Sort testing logic:** Initial approach using string position had some errors


**I reviewed all the files generated, refactored some of them and tried to test all the steps:**
1. **Ran tests after each step:** `php artisan test`
2. **Manual testing:** Started dev server and queue worker, tested all features in browser
3. **Code style check:** Ran `./vendor/bin/pint --test` before commits
4. **Database inspection:** Verified migrations, relationships, and data integrity (before and after I had to change some data types)

The code quality in some areas like tests, UI and data synchronization was not good and required more manual changes than the other parts like models, migrations, controllers and using the data in the views. 

Some improvements for the best practices were included in the last Pull Request checking some missing files with the agentic tool, like DTOs, interfaces and caching. I included the changes in a single PR to finish the project in the test's allocated time and listed the improvements in the PR.

