#!/usr/bin/python3

#     Copyright 2021. FastyBird s.r.o.
#
#     Licensed under the Apache License, Version 2.0 (the "License");
#     you may not use this file except in compliance with the License.
#     You may obtain a copy of the License at
#
#         http://www.apache.org/licenses/LICENSE-2.0
#
#     Unless required by applicable law or agreed to in writing, software
#     distributed under the License is distributed on an "AS IS" BASIS,
#     WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
#     See the License for the specific language governing permissions and
#     limitations under the License.

"""
Triggers module DI container
"""

# pylint: disable=no-value-for-parameter

# Library dependencies
from enum import Enum
from typing import Dict
from kink import di
from pony.orm.dbproviders.mysql import MySQLProvider
from pony.orm.dbproviders.sqlite import SQLiteProvider

# Library libs
from triggers_module.converters import EnumConverter
from triggers_module.exchange import ModuleExchange
from triggers_module.models import db
from triggers_module.repositories import (
    TriggersRepository,
    TriggersControlsRepository,
    ActionsRepository,
    ConditionsRepository,
)

default_settings: Dict[str, str or int or bool or None] = {
    "host": "127.0.0.1",
    "port": 3306,
    "username": None,
    "password": None,
    "database": "fb_triggers_module",
    "create_tables": False,
}


def create_container(settings: Dict[str, str or int or bool or None]) -> None:
    """Register triggers module services"""
    module_settings: Dict[str, str or int or bool or None] = {**default_settings, **settings}

    # Add ENUM converter
    MySQLProvider.converter_classes.append((Enum, EnumConverter))
    SQLiteProvider.converter_classes.append((Enum, EnumConverter))

    di["fb-triggers-module_database"] = db

    di[TriggersRepository] = TriggersRepository()
    di["fb-triggers-module_trigger-repository"] = di[TriggersRepository]
    di[TriggersControlsRepository] = TriggersControlsRepository()
    di["fb-triggers-module_trigger-control-repository"] = di[TriggersControlsRepository]
    di[ActionsRepository] = ActionsRepository()
    di["fb-triggers-module_action-repository"] = di[ActionsRepository]
    di[ConditionsRepository] = ConditionsRepository()
    di["fb-triggers-module_condition-repository"] = di[ConditionsRepository]

    di[ModuleExchange] = ModuleExchange()
    di["fb-triggers-module_exchange"] = di[ModuleExchange]

    db.bind(
        provider="mysql",
        host=module_settings.get("host", "127.0.0.1"),
        port=int(module_settings.get("port", 3306)),
        user=module_settings.get("username", None),
        passwd=module_settings.get("password", None),
        db=module_settings.get("database", None),
    )
    db.generate_mapping(create_tables=settings.get("create_tables", False))
