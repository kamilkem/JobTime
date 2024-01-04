@default
Feature:

    Scenario: List teams for current user
        Given I am a user_0@jobtime.app
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/user/teams"
        Then the response status code should be 200

    Scenario: List teams for current user as anonymous
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/user/teams"
        Then the response status code should be 401
