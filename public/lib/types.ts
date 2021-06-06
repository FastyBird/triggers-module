import { ModulePrefix } from '@fastybird/modules-metadata'

import { TJsonaModel } from 'jsona/lib/JsonaTypes'

import { TriggerEntityTypes } from '@/lib/models/triggers/types'
import { ActionEntityTypes } from '@/lib/models/actions/types'
import { ConditionEntityTypes } from '@/lib/models/conditions/types'
import { NotificationEntityTypes } from '@/lib/models/notifications/types'

export interface TriggerJsonModelInterface extends TJsonaModel {
  id: string
  type: TriggerEntityTypes
}

export interface ActionJsonModelInterface extends TJsonaModel {
  id: string
  type: ActionEntityTypes
}

export interface ConditionJsonModelInterface extends TJsonaModel {
  id: string
  type: ConditionEntityTypes
}

export interface NotificationJsonModelInterface extends TJsonaModel {
  id: string
  type: NotificationEntityTypes
}

export interface RelationInterface extends TJsonaModel {
  id: string
  type: TriggerEntityTypes | ActionEntityTypes | NotificationEntityTypes | ConditionEntityTypes
}

export const ModuleApiPrefix = `/${ModulePrefix.MODULE_TRIGGERS_PREFIX}`

// STORE
// =====

export enum SemaphoreTypes {
  FETCHING = 'fetching',
  GETTING = 'getting',
  CREATING = 'creating',
  UPDATING = 'updating',
  DELETING = 'deleting',
}
