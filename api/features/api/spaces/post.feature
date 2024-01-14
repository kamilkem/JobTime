@default
Feature:

    Scenario: Create space
        Given I am a user_0@jobtime.app
        When I add "content-type" header equal to "application/ld+json"
        And I add "accept" header equal to "application/ld+json"
        And I send a POST request to "/spaces" with body:
        """
        {
            "team": "/teams/7050b48e-649b-3ede-9417-bf795435cee3",
            "name": "Example",
            "description": "Example"
        }
        """
        Then the response status code should be 201
        And the JSON node "team.id" should be equal to "7050b48e-649b-3ede-9417-bf795435cee3"
        And the JSON node "name" should be equal to "Example"
        And the JSON node "description" should be equal to "Example"

    Scenario Outline: Create space as user
        Given I am a <user>
        When I add "content-type" header equal to "application/ld+json"
        And I add "accept" header equal to "application/ld+json"
        And I send a POST request to "/spaces" with body:
        """
        {
            "team": "/teams/7050b48e-649b-3ede-9417-bf795435cee3",
            "name": "Example",
            "description": "Example"
        }
        """
        Then the response status code should be <code>

        Examples:
            | user               | code |
            | user_0@jobtime.app | 201  |
            | user_1@jobtime.app | 201  |
            | user_5@jobtime.app | 403  |

    Scenario: Create space as anonymous
        When I add "content-type" header equal to "application/ld+json"
        And I add "accept" header equal to "application/ld+json"
        And I send a POST request to "/spaces" with body:
        """
        {
            "team": "/teams/7050b48e-649b-3ede-9417-bf795435cee3",
            "name": "Example",
            "description": "Example"
        }
        """
        Then the response status code should be 401
