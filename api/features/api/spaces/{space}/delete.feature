@default
Feature:

    Scenario: Delete space
        Given I am a user_0@jobtime.app
        When I add "accept" header equal to "application/ld+json"
        And I send a DELETE request to "/spaces/8d266d7a-3491-3219-89b8-e4fb1a627e35"
        Then the response status code should be 204

    Scenario Outline: Delete space as user
        Given I am a <user>
        When I add "accept" header equal to "application/ld+json"
        And I send a DELETE request to "/spaces/8d266d7a-3491-3219-89b8-e4fb1a627e35"
        Then the response status code should be <code>

        Examples:
            | user               | code |
            | user_0@jobtime.app | 204  |
            | user_1@jobtime.app | 204  |
            | user_5@jobtime.app | 403  |

    Scenario: Delete space as anonymous
        When I add "accept" header equal to "application/ld+json"
        And I send a DELETE request to "/spaces/8d266d7a-3491-3219-89b8-e4fb1a627e35"
        Then the response status code should be 401
