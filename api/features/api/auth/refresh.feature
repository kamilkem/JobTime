@default
Feature:

    Scenario: Try refresh token with providing valid token
        And I send a POST request to "/auth/refresh?refresh_token=08880aff927f921ff157d7e2571c217277a538a6db7cb47337eba188ee8e89cc7dca5f3e4c406067c2e159152c64a64814b0c2bc0941aecfcebdb0bef35a3de6"
        Then the response status code should be 200
        And the JSON node "token" should exist
        And the JSON node "refresh_token" should exist

    Scenario: Try refresh token with providing invalid token
        When I send a POST request to "/auth/refresh?refresh_token=invalidtoken"
        Then the response status code should be 401
        And the JSON node "message" should be equal to "JWT Refresh Token Not Found"
