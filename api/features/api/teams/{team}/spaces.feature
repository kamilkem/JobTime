@default
Feature:

    Scenario: List spaces for team
        Given I am a user_0@jobtime.app
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/teams/7050b48e-649b-3ede-9417-bf795435cee3/spaces"
        Then the response status code should be 200

    Scenario: List spaces for team as anonymous
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/teams/7050b48e-649b-3ede-9417-bf795435cee3/spaces"
        Then the response status code should be 401
