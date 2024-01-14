@default
Feature:

    Scenario: List user teams
        Given I am a user_0@jobtime.app
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/user/teams"
        Then the response status code should be 200
        And the JSON node "hydra:member[0].id" should exist
        And the JSON node "hydra:member[0].name" should exist
        And the JSON node "hydra:member[0].createdAt" should exist

    Scenario: List user teams as anonymous
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/user/teams"
        Then the response status code should be 401
