Nakade Season Module
====

This module is for creating a new season. It contains a complex workflow, widgets,
emails and the calculation of the league rounds by the swing system.


Workflow
----

* Creation of a new season with rules and dates
* Inviting participants by email or widget
* Create leagues and assignment of registered participants
* Configuration of match days
* Create a match schedule
* Activate new season and send match dates to all participants


Details
----

* View create for all steps
    * Steps available by buttons
    * Actual stage enables or disables buttons
    * Stage is validated
    * Undo steps except invitation
* Invitation
    * Email to selected players
    * Confirm by link in email
    * Widget for register directly for new season
    * Widget dependant on stage
* Configuration
    * Default values from season
    * Creates all match days on maximum players in a league
    * Edit each single match date
* Schedule Service
    * Calculation based on swing system
    * Impair leagues possible
    * Alternating colors
* Activate Season
    * Unlock creation of a new season
    * Email to all participants containing rules and match dates


TO DO
----

* ACL to user
* create new associations
* registration widget for more than one season
* limit widget access to well-known user
* enable private invitation (emails only)
* send queued mails by cron job


License
----

Copyright (c) 2014, Dr. Holger Maerz
All rights reserved.

Redistribution and use in source and binary forms, with or without modification,
are permitted provided that the following conditions are met:

* Redistributions of source code must retain the above copyright notice, this
  list of conditions and the following disclaimer.

* Redistributions in binary form must reproduce the above copyright notice, this
  list of conditions and the following disclaimer in the documentation and/or
  other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR
ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
