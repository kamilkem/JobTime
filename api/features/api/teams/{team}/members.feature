@default
Feature:

    Scenario: List members for team
        Given I am a user_0@jobtime.app
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/teams/7050b48e-649b-3ede-9417-bf795435cee3/members"
        Then the response status code should be 200
        And the JSON node "hydra:member[0].id" should exist
        And the JSON node "hydra:member[0].team" should exist
        And the JSON node "hydra:member[0].user" should exist
        And the JSON node "hydra:member[0].createdAt" should exist

    Scenario Outline: List members for team as user
        Given I am a <user>
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/teams/7050b48e-649b-3ede-9417-bf795435cee3/members"
        Then the response status code should be <code>

        Examples:
            | user               | code |
            | user_0@jobtime.app | 200  |
            | user_1@jobtime.app | 200  |
            | user_5@jobtime.app | 403  |

    Scenario: List members for team as anonymous
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/teams/7050b48e-649b-3ede-9417-bf795435cee3/members"
        Then the response status code should be 401
