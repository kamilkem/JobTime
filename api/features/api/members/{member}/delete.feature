@default
Feature:

    Scenario: Delete member
        Given I am a user_0@jobtime.app
        When I add "accept" header equal to "application/ld+json"
        And I send a DELETE request to "/members/edce8cbc-4732-3c9b-bf1f-5826c5ba3195"
        Then the response status code should be 204

    Scenario Outline: Delete member as user
        Given I am a <user>
        When I add "accept" header equal to "application/ld+json"
        And I send a DELETE request to "/members/edce8cbc-4732-3c9b-bf1f-5826c5ba3195"
        Then the response status code should be <code>

        Examples:
            | user               | code |
            | user_0@jobtime.app | 204  |
            | user_1@jobtime.app | 403  |
            | user_5@jobtime.app | 403  |

    Scenario: Delete member as anonymous
        When I add "accept" header equal to "application/ld+json"
        And I send a DELETE request to "/members/edce8cbc-4732-3c9b-bf1f-5826c5ba3195"
        Then the response status code should be 401
