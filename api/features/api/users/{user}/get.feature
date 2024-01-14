@default
Feature:

    Scenario: Get user
        Given I am a user_0@jobtime.app
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/users/26efedc4-7a63-38a2-b442-9de33933dd06"
        Then the response status code should be 200
        And the JSON node "id" should exist
        And the JSON node "email" should exist
        And the JSON node "firstName" should exist
        And the JSON node "lastName" should exist
        And the JSON node "createdAt" should exist

    Scenario: Get user as anonymous
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/users/26efedc4-7a63-38a2-b442-9de33933dd06"
        Then the response status code should be 401

    Scenario: Get user as not its owner
        Given I am a user_1@jobtime.app
        When I add "accept" header equal to "application/ld+json"
        And I send a GET request to "/users/26efedc4-7a63-38a2-b442-9de33933dd06"
        Then the response status code should be 200
