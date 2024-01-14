@default
Feature:

    Scenario: Get space
        Given I am a user_0@jobtime.app
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/spaces/8d266d7a-3491-3219-89b8-e4fb1a627e35"
        Then the response status code should be 200
        And the JSON node "id" should exist
        And the JSON node "team" should exist
        And the JSON node "name" should exist
        And the JSON node "description" should exist
        And the JSON node "directories" should exist
        And the JSON node "createdAt" should exist
        And the JSON node "createdBy" should exist

    Scenario Outline: Get space as user
        Given I am a <user>
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/spaces/8d266d7a-3491-3219-89b8-e4fb1a627e35"
        Then the response status code should be <code>

        Examples:
            | user               | code |
            | user_0@jobtime.app | 200  |
            | user_1@jobtime.app | 200  |
            | user_5@jobtime.app | 403  |

    Scenario: Get space as anonymous
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/spaces/8d266d7a-3491-3219-89b8-e4fb1a627e35"
        Then the response status code should be 401
