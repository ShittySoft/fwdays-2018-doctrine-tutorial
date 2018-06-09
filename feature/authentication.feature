Feature: A user can log in and logout

  Scenario: a user can register with a valid email and password
    Given there are no registered users
    When a user registers with the website
    Then the user can log into the website

  Scenario: a user cannot register with a duplicate email address
    Given "bob@fwdays.com" is a registered user
    Then I cannot register a user "bob@fwdays.com"

  Scenario: A user can log into the website
    Given there is a registered user
    Then the user can log into the website

  Scenario: A user cannot log into the website
    Given there is a registered user
    Then the user cannot log into the website with a non-matching password

