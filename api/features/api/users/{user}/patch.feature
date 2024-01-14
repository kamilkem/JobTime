@default
Feature:

    Scenario: Patch user
        Given I am a user_0@jobtime.app
        When I add "content-type" header equal to "application/merge-patch+json"
        And I add "accept" header equal to "application/ld+json"
        And I send a PATCH request to "/users/26efedc4-7a63-38a2-b442-9de33933dd06" with body:
        """
        {
            "firstName": "Example",
            "lastName": "Example"
        }
        """
        Then the response status code should be 200
        And the JSON node "firstName" should be equal to "Example"
        And the JSON node "lastName" should be equal to "Example"

    Scenario: Patch user as anonymous
        When I add "content-type" header equal to "application/ld+json"
        And I add "accept" header equal to "application/ld+json"
        And I send a PATCH request to "/users/26efedc4-7a63-38a2-b442-9de33933dd06" with body:
        """
        {
            "firstName": "Example",
            "lastName": "Example"
        }
        """
        Then the response status code should be 401

    Scenario: Patch user as not its owner
        Given I am a user_1@jobtime.app
        When I add "content-type" header equal to "application/merge-patch+json"
        And I add "accept" header equal to "application/ld+json"
        And I send a PATCH request to "/users/26efedc4-7a63-38a2-b442-9de33933dd06" with body:
        """
        {
            "firstName": "Example",
            "lastName": "Example"
        }
        """
        Then the response status code should be 403
