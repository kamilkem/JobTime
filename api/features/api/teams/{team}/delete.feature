@default
Feature:

    Scenario: Delete team
        Given I am a user_0@jobtime.app
        When I add "accept" header equal to "application/ld+json"
        And I send a DELETE request to "/teams/7050b48e-649b-3ede-9417-bf795435cee3"
        Then the response status code should be 204

    Scenario Outline: Delete team as user
        Given I am a <user>
        When I add "accept" header equal to "application/ld+json"
        And I send a DELETE request to "/teams/7050b48e-649b-3ede-9417-bf795435cee3"
        Then the response status code should be <code>

        Examples:
            | user               | code |
            | user_0@jobtime.app | 204  |
            | user_1@jobtime.app | 403  |
            | user_5@jobtime.app | 403  |

    Scenario: Delete team as anonymous
        When I add "accept" header equal to "application/ld+json"
        And I send a DELETE request to "/teams/7050b48e-649b-3ede-9417-bf795435cee3"
        Then the response status code should be 401
