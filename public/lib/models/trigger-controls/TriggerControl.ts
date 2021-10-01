import {
  Fields,
  Model,
} from '@vuex-orm/core'

import Trigger from '@/lib/models/triggers/Trigger'
import { TriggerInterface } from '@/lib/models/triggers/types'
import {
  TriggerControlEntityTypes,
  TriggerControlInterface,
} from '@/lib/models/trigger-controls/types'

// ENTITY MODEL
// ============
export default class TriggerControl extends Model implements TriggerControlInterface {
  static get entity(): string {
    return 'triggers_trigger_control'
  }

  static fields(): Fields {
    return {
      id: this.string(''),
      type: this.string(TriggerControlEntityTypes.CONTROL),

      name: this.string(''),

      trigger: this.belongsTo(Trigger, 'id'),
      triggerBackward: this.hasOne(Trigger, 'id', 'triggerId'),

      triggerId: this.string(''),
    }
  }

  id!: string
  type!: TriggerControlEntityTypes

  name!: string

  trigger!: TriggerInterface | null
  triggerBackward!: TriggerInterface | null

  triggerId!: string

  get triggerInstance(): TriggerInterface | null {
    return this.trigger
  }

  static async get(trigger: TriggerInterface, id: string): Promise<boolean> {
    return await TriggerControl.dispatch('get', {
      trigger,
      id,
    })
  }

  static async fetch(trigger: TriggerInterface): Promise<boolean> {
    return await TriggerControl.dispatch('fetch', {
      trigger,
    })
  }

  static transmitCommand(control: TriggerControl, value?: string | number | boolean | null): Promise<boolean> {
    return TriggerControl.dispatch('transmitCommand', {
      control,
      value,
    })
  }

  static reset(): Promise<void> {
    return TriggerControl.dispatch('reset')
  }
}
