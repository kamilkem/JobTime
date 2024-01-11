@default
Feature:

    Scenario: List spaces for team
        Given I am a user_0@jobtime.app
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/teams/0a655210-1c2f-37e6-81ab-d3f1cc69f3df/spaces"
        Then the response status code should be 200

    Scenario: List spaces for team as anonymous
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/teams/0a655210-1c2f-37e6-81ab-d3f1cc69f3df/spaces"
        Then the response status code should be 401
