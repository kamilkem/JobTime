@default
Feature:

    Scenario: Create team
        Given I am a user_0@jobtime.app
        When I add "content-type" header equal to "application/ld+json"
        And I add "accept" header equal to "application/ld+json"
        And I send a POST request to "/teams" with body:
        """
        {
            "name": "Example"
        }
        """
        Then the response status code should be 201
        And the JSON node "name" should be equal to "Example"

    Scenario: Create team as anonymous
        When I add "content-type" header equal to "application/ld+json"
        And I add "accept" header equal to "application/ld+json"
        And I send a POST request to "/teams" with body:
        """
        {
            "name": "Example"
        }
        """
        Then the response status code should be 401
