@default
Feature:

    Scenario: List spaces for team
        Given I am a user_0@jobtime.app
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/teams/7050b48e-649b-3ede-9417-bf795435cee3/spaces"
        Then the response status code should be 200
        And the JSON node "hydra:member[0].id" should exist
        And the JSON node "hydra:member[0].team" should exist
        And the JSON node "hydra:member[0].name" should exist
        And the JSON node "hydra:member[0].description" should exist
        And the JSON node "hydra:member[0].directories" should exist
        And the JSON node "hydra:member[0].createdAt" should exist
        And the JSON node "hydra:member[0].createdBy" should exist

    Scenario Outline: List spaces for team as user
        Given I am a <user>
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/teams/7050b48e-649b-3ede-9417-bf795435cee3/spaces"
        Then the response status code should be <code>

        Examples:
            | user               | code |
            | user_0@jobtime.app | 200  |
            | user_1@jobtime.app | 200  |
            | user_5@jobtime.app | 403  |

    Scenario: List spaces for team as anonymous
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/teams/7050b48e-649b-3ede-9417-bf795435cee3/spaces"
        Then the response status code should be 401
