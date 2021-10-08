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
    TriggerRepository,
    TriggerControlRepository,
    ActionRepository,
    ConditionRepository,
)


def create_container(settings: Dict) -> None:
    """Register triggers module services"""
    # Add ENUM converter
    MySQLProvider.converter_classes.append((Enum, EnumConverter))
    SQLiteProvider.converter_classes.append((Enum, EnumConverter))

    di["fb-triggers-module_database"] = db

    di[TriggerRepository] = TriggerRepository()
    di["fb-triggers-module_trigger-repository"] = di[TriggerRepository]
    di[TriggerControlRepository] = TriggerControlRepository()
    di["fb-triggers-module_trigger-control-repository"] = di[TriggerControlRepository]
    di[ActionRepository] = ActionRepository()
    di["fb-triggers-module_action-repository"] = di[ActionRepository]
    di[ConditionRepository] = ConditionRepository()
    di["fb-triggers-module_condition-repository"] = di[ConditionRepository]

    di[ModuleExchange] = ModuleExchange()
    di["fb-triggers-module_exchange"] = di[ModuleExchange]

    db.bind(
        provider="mysql",
        host=settings.get("host", "127.0.0.1"),
        user=settings.get("user", None),
        passwd=settings.get("passwd", None),
        db=settings.get("db", None),
    )
    db.generate_mapping(create_tables=settings.get("create_tables", False))
