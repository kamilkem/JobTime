@default
Feature:

    Scenario: Get member
        Given I am a user_0@jobtime.app
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/members/edce8cbc-4732-3c9b-bf1f-5826c5ba3195"
        Then the response status code should be 200
        And the JSON node "id" should exist
        And the JSON node "team" should exist
        And the JSON node "user" should exist
        And the JSON node "createdAt" should exist

    Scenario Outline: Get member as user
        Given I am a <user>
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/members/edce8cbc-4732-3c9b-bf1f-5826c5ba3195"
        Then the response status code should be <code>

        Examples:
            | user               | code |
            | user_0@jobtime.app | 200  |
            | user_1@jobtime.app | 200  |
            | user_5@jobtime.app | 403  |

    Scenario: Get member as anonymous
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/members/edce8cbc-4732-3c9b-bf1f-5826c5ba3195"
        Then the response status code should be 401
