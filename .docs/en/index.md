# Getting started

This module adds support for managing [FastyBird](https://www.fastybird.com) IoT triggers.

## Installation

### Application backend in PHP

The best way to install **fastybird/triggers-module** is using [Composer](https://getcomposer.org/).

> If you don't have Composer yet, [download it](https://getcomposer.org/download/) following the instructions.

#### Create new project

If you don't have a project created yet you could start with Nette base project.

You could create new project with simple composer command.

```sh
composer create-project nette/web-project path/to/install
```

Everything required will be then installed in the provided folder.

```sh
cd path/to/install
```

#### Install module

Module could be added to your project with composer command:

```sh
composer require fastybird/triggers-module
```

### Application frontend in JS

The best way to install **@fastybird/triggers-module** is using [Yarn](https://yarnpkg.com/):

```sh
yarn add @fastybird/triggers-module
```

or if you prefer npm:

```sh
npm install @fastybird/triggers-module
```

### Application additional backend in Python

The best way to install **fastybird-triggers-module** is using [pip](https://pip.pypa.io/):

```sh
pip install fastybird-triggers-module
```

## Configuration

This module is dependent on other Nette extensions. All this extensions have to enabled and configured in NEON
configuration file.

Example configuration could be
found [here](https://github.com/FastyBird/triggers-module/blob/master/config/example.neon)

## Initialization

This module is using database, and need some initial data to be inserted into it.

Execution of command is dependend on you current implementation. This module is dependend
on [contribute/console](https://github.com/contributte/console) extension, so check it out to get know how to configure
your console entrypoint.

After creating console entrypoint you could call module console command:

```sh
your-console-entrypoint fb:triggers-module:initialize
```

This console command is interactive and will ask for all required information.

## Running module interface

This module is dependent on [fastybird/web-server](https://github.com/FastyBird/web-server) which is server-less web
server for serving API content. This module is registering its routes to this webserver automatically. All what you have
to do is start this server with your console entrypoint:

```sh
your-console-entrypoint fb:web-server:start
```

After successful start, server is listening for incoming http api request messages from clients.

## Register Vuex ORM models

This module could be registered in your Vuex ORM instance

```js
import VuexORM, { Database } from '@vuex-orm/core'
import TriggersModule from '@fastybird/triggers-module'

// Create new instance of Database
const database = new Database()

VuexORM.use(TriggersModule, { database })

export default {
  plugins: [
    VuexORM.install(database),
  ],
}
```

## Registering PonyORM models

This module has all entities configured for Python PonyORM. Registration needs just database instance:

```python
from pony.orm import Database
from fb_triggers_module.models import define_entities

db: Database = Database()

TriggerEntity, ManualTriggerEntity, AutomaticTriggerEntity, \
    ActionEntity, PropertyActionEntity, DevicePropertyActionEntity, ChannelPropertyActionEntity, \
    NotificationEntity, EmailNotificationEntity, SmsNotificationEntity, \
    ConditionEntity, TimeConditionEntity, DateConditionEntity, \
    PropertyConditionEntity, ChannelPropertyConditionEntity, DevicePropertyConditionEntity, \
    triggers_repository \
    = define_entities(db)
```
