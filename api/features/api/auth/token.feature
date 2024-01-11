@default
Feature:

    Scenario: Reach JWT token
        When I add "content-type" header equal to "application/ld+json"
        And I add "accept" header equal to "application/ld+json"
        And I send a POST request to "/auth/token" with body:
        """
        {
            "email": "user_0@jobtime.app",
            "password": "password"
        }
        """
        Then the response status code should be 200
        And the JSON node "token" should exist
        And the JSON node "refresh_token" should exist

    Scenario: Reach JWT token with providing bad credentials
        When I add "content-type" header equal to "application/ld+json"
        And I add "accept" header equal to "application/ld+json"
        And I send a POST request to "/auth/token" with body:
        """
        {
            "email": "notexisting@jobtime.app",
            "password": "password"
        }
        """
        Then the response status code should be 401
