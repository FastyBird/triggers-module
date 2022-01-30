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
Triggers module device states managers module
"""

# Python base dependencies
from abc import abstractmethod
from datetime import datetime
from typing import Dict, Union

# Library dependencies
from fastybird_metadata.types import ButtonPayload, SwitchPayload

# Library libs
from fastybird_triggers_module.entities.action import ActionEntity
from fastybird_triggers_module.entities.condition import ConditionEntity
from fastybird_triggers_module.state.action import IActionState
from fastybird_triggers_module.state.condition import IConditionState


class IActionsStatesManager:
    """
    Triggers actions states manager

    @package        FastyBird:TriggersModule!
    @module         managers/state

    @author         Adam Kadlec <adam.kadlec@fastybird.com>
    """

    @abstractmethod
    def create(
        self,
        action: ActionEntity,
        data: Dict[str, Union[str, int, float, bool, datetime, ButtonPayload, SwitchPayload, None]],
    ) -> IActionState:
        """Create new action state record"""

    # -----------------------------------------------------------------------------

    @abstractmethod
    def update(
        self,
        action: ActionEntity,
        state: IActionState,
        data: Dict[str, Union[str, int, float, bool, datetime, ButtonPayload, SwitchPayload, None]],
    ) -> IActionState:
        """Update existing action state record"""

    # -----------------------------------------------------------------------------

    @abstractmethod
    def delete(
        self,
        action: ActionEntity,
        state: IActionState,
    ) -> bool:
        """Delete existing action state"""


class IConditionsStatesManager:
    """
    Triggers conditions states manager

    @package        FastyBird:TriggersModule!
    @module         managers/state

    @author         Adam Kadlec <adam.kadlec@fastybird.com>
    """

    @abstractmethod
    def create(
        self,
        condition: ConditionEntity,
        data: Dict[str, Union[str, int, float, bool, datetime, ButtonPayload, SwitchPayload, None]],
    ) -> IConditionState:
        """Create new condition state record"""

    # -----------------------------------------------------------------------------

    @abstractmethod
    def update(
        self,
        condition: ConditionEntity,
        state: IConditionState,
        data: Dict[str, Union[str, int, float, bool, datetime, ButtonPayload, SwitchPayload, None]],
    ) -> IConditionState:
        """Update existing condition state record"""

    # -----------------------------------------------------------------------------

    @abstractmethod
    def delete(
        self,
        condition: ConditionEntity,
        state: IConditionState,
    ) -> bool:
        """Delete existing condition state"""
