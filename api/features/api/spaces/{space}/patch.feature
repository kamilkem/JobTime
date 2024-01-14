@default
Feature:

    Scenario: Patch space
        Given I am a user_0@jobtime.app
        When I add "content-type" header equal to "application/merge-patch+json"
        And I add "accept" header equal to "application/ld+json"
        And I send a PATCH request to "/spaces/8d266d7a-3491-3219-89b8-e4fb1a627e35" with body:
        """
        {
            "name": "Example",
            "description": "Example"
        }
        """
        Then the response status code should be 200
        And the JSON node "name" should be equal to "Example"
        And the JSON node "description" should be equal to "Example"

    Scenario Outline: Patch space as user
        Given I am a <user>
        When I add "content-type" header equal to "application/merge-patch+json"
        And I add "accept" header equal to "application/ld+json"
        And I send a PATCH request to "/spaces/8d266d7a-3491-3219-89b8-e4fb1a627e35" with body:
        """
        {
            "name": "Example",
            "description": "Example"
        }
        """
        Then the response status code should be <code>

        Examples:
            | user               | code |
            | user_0@jobtime.app | 200  |
            | user_1@jobtime.app | 200  |
            | user_5@jobtime.app | 403  |

    Scenario: Patch space as anonymous
        When I add "content-type" header equal to "application/ld+json"
        And I add "accept" header equal to "application/ld+json"
        And I send a PATCH request to "/spaces/8d266d7a-3491-3219-89b8-e4fb1a627e35" with body:
        """
        {
            "name": "Example",
            "description": "Example"
        }
        """
        Then the response status code should be 401
