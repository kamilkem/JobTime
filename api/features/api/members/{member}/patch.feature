@default
Feature:

    Scenario: Patch member
        Given I am a user_0@jobtime.app
        When I add "content-type" header equal to "application/merge-patch+json"
        And I add "accept" header equal to "application/ld+json"
        And I send a PATCH request to "/members/edce8cbc-4732-3c9b-bf1f-5826c5ba3195" with body:
        """
        {
            "owner": true
        }
        """
        Then the response status code should be 200
        And the JSON node "owner" should be equal to "true"

    Scenario Outline: Patch member as user
        Given I am a <user>
        When I add "content-type" header equal to "application/merge-patch+json"
        And I add "accept" header equal to "application/ld+json"
        And I send a PATCH request to "/members/edce8cbc-4732-3c9b-bf1f-5826c5ba3195" with body:
        """
        {
            "owner": true
        }
        """
        Then the response status code should be <code>

        Examples:
            | user               | code |
            | user_0@jobtime.app | 200  |
            | user_1@jobtime.app | 403  |
            | user_5@jobtime.app | 403  |

    Scenario: Patch member as anonymous
        When I add "content-type" header equal to "application/ld+json"
        And I add "accept" header equal to "application/ld+json"
        And I send a PATCH request to "/members/edce8cbc-4732-3c9b-bf1f-5826c5ba3195" with body:
        """
        {
            "owner": true
        }
        """
        Then the response status code should be 401
