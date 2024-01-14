@default
Feature:

    Scenario: Patch team
        Given I am a user_0@jobtime.app
        When I add "content-type" header equal to "application/merge-patch+json"
        And I add "accept" header equal to "application/ld+json"
        And I send a PATCH request to "/teams/7050b48e-649b-3ede-9417-bf795435cee3" with body:
        """
        {
            "name": "Example"
        }
        """
        Then the response status code should be 200
        And the JSON node "name" should be equal to "Example"

    Scenario Outline: Patch team as team member
        Given I am a <user>
        When I add "content-type" header equal to "application/merge-patch+json"
        And I add "accept" header equal to "application/ld+json"
        And I send a PATCH request to "/teams/7050b48e-649b-3ede-9417-bf795435cee3" with body:
        """
        {
            "name": "Example"
        }
        """
        Then the response status code should be <code>

        Examples:
            | user               | code |
            | user_0@jobtime.app | 200  |
            | user_1@jobtime.app | 403  |
            | user_5@jobtime.app | 403  |

    Scenario: Patch team as anonymous
        When I add "content-type" header equal to "application/ld+json"
        And I add "accept" header equal to "application/ld+json"
        And I send a PATCH request to "/teams/7050b48e-649b-3ede-9417-bf795435cee3" with body:
        """
        {
            "name": "Example"
        }
        """
        Then the response status code should be 401
