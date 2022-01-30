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
Triggers module connectors connector worker exchange module
"""

# Python base dependencies
import logging
from typing import Dict, Optional, Union

# Library dependencies
from fastybird_exchange.consumer import IConsumer
from fastybird_metadata.routing import RoutingKey
from fastybird_metadata.types import ConnectorOrigin, ModuleOrigin, PluginOrigin

# Library libs
from fastybird_triggers_module.automation.queue import (
    AutomationQueue,
    ConsumeEntityMessageQueueItem,
)


class AutomationConsumer(IConsumer):  # pylint: disable=too-few-public-methods
    """
    Data exchange service container

    @package        FastyBird:TriggersModule!
    @module         connectors/consumer

    @author         Adam Kadlec <adam.kadlec@fastybird.com>
    """

    __ENTITY_PREFIX_KEY: str = "fb.exchange.module.entity"
    __ENTITY_REPORTED_KEY: str = "reported"
    __ENTITY_CREATED_KEY: str = "created"
    __ENTITY_UPDATED_KEY: str = "updated"
    __ENTITY_DELETED_KEY: str = "deleted"

    __queue: AutomationQueue

    __logger: logging.Logger

    # -----------------------------------------------------------------------------

    def __init__(  # pylint: disable=too-many-arguments
        self,
        queue: AutomationQueue,
        logger: logging.Logger = logging.getLogger("dummy"),
    ) -> None:
        self.__queue = queue

        self.__logger = logger

    # -----------------------------------------------------------------------------

    def consume(
        self,
        origin: Union[ModuleOrigin, PluginOrigin, ConnectorOrigin],
        routing_key: RoutingKey,
        data: Optional[Dict],
    ) -> None:
        """Processing message received by exchange service"""
        if data is not None:
            if str(routing_key.value).startswith(self.__ENTITY_PREFIX_KEY):
                self.__queue.append(
                    ConsumeEntityMessageQueueItem(
                        origin=origin,
                        routing_key=routing_key,
                        data=data,
                    )
                )

        else:
            self.__logger.warning("Received data message without data")