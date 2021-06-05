import {
  Fields,
  Item,
  Model,
} from '@vuex-orm/core'
import { TriggerControlAction } from '@fastybird/modules-metadata'

import get from 'lodash/get'

import {
  CreateAutomaticTriggerInterface,
  CreateManualTriggerInterface,
  TriggerEntityTypes,
  TriggerInterface,
  TriggerUpdateInterface,
} from '@/lib/triggers/types'

import Action from '@/lib/actions/Action'
import { ActionInterface } from '@/lib/actions/types'
import Condition from '@/lib/conditions/Condition'
import {
  ConditionEntityTypes,
  ConditionInterface,
} from '@/lib/conditions/types'
import Notification from '@/lib/notifications/Notification'
import { NotificationInterface } from '@/lib/notifications/types'

// ENTITY MODEL
// ============
export default class Trigger extends Model implements TriggerInterface {
  static get entity(): string {
    return 'trigger'
  }

  static fields(): Fields {
    return {
      id: this.string(''),
      type: this.string(''),

      draft: this.boolean(false),

      name: this.string(''),
      comment: this.string(null).nullable(),
      enabled: this.boolean(true),

      owner: this.string(null).nullable(),

      // Relations
      relationshipNames: this.attr([]),

      actions: this.hasMany(Action, 'triggerId'),
      conditions: this.hasMany(Condition, 'triggerId'),
      notifications: this.hasMany(Notification, 'triggerId'),

      // Channel property trigger
      device: this.string(null).nullable(),
      channel: this.string(null).nullable(),
      property: this.string(null).nullable(),
      operator: this.string(null).nullable(),
      operand: this.string(null).nullable(),
    }
  }

  id!: string
  type!: TriggerEntityTypes

  draft!: boolean

  name!: string
  comment!: string | null
  enabled!: boolean

  owner!: string | null

  relationshipNames!: Array<string>

  actions!: Array<ActionInterface>
  conditions!: Array<ConditionInterface>
  notifications!: Array<NotificationInterface>

  get isEnabled(): boolean {
    return this.enabled
  }

  get icon(): string {
    return 'magic'
  }

  get description(): string {
    if (this.comment !== null && this.comment !== '') {
      return this.comment
    }

    const storeInstance = Trigger.store()

    if (!Object.prototype.hasOwnProperty.call(storeInstance, '$i18n')) {
      return ''
    }

    if (this.isTime) {
      let days: Array<string> = []

      const schedule = Condition
        .query()
        .where('triggerId', this.id)
        .where('type', ConditionEntityTypes.TIME)
        .first()

      if (schedule !== null) {
        if (schedule.days.length === 7) {
          // @ts-ignore
          days.push(storeInstance.$i18n.t('triggersModule.description.everyday').toString())
        } else {
          days = []

          for (const day of schedule.days) {
            switch (day) {
              case 1:
                // @ts-ignore
                days.push(storeInstance.$i18n.t('triggersModule.description.days.mon').toString())
                break

              case 2:
                // @ts-ignore
                days.push(storeInstance.$i18n.t('triggersModule.description.days.tue').toString())
                break

              case 3:
                // @ts-ignore
                days.push(storeInstance.$i18n.t('triggersModule.description.days.wed').toString())
                break

              case 4:
                // @ts-ignore
                days.push(storeInstance.$i18n.t('triggersModule.description.days.thu').toString())
                break

              case 5:
                // @ts-ignore
                days.push(storeInstance.$i18n.t('triggersModule.description.days.fri').toString())
                break

              case 6:
                // @ts-ignore
                days.push(storeInstance.$i18n.t('triggersModule.description.days.sat').toString())
                break

              case 7:
                // @ts-ignore
                days.push(storeInstance.$i18n.t('triggersModule.description.days.sun').toString())
                break
            }
          }
        }

        // @ts-ignore
        return storeInstance.$i18n.t('triggersModule.description.scheduledTrigger', {
          days: days.join(', '),
          // @ts-ignore
          time: storeInstance.$dateFns.format(new Date(schedule.time), get(storeInstance.getters['session/getAccount'](), 'timeFormat', 'HH:mm')),
        }).toString()
      }
    }

    // @ts-ignore
    return this.isAutomatic ? storeInstance.$i18n.t('triggersModule.description.automaticTrigger').toString() : storeInstance.$i18n.t('triggersModule.description.manualTrigger').toString()
  }

  get isAutomatic(): boolean {
    return this.type === TriggerEntityTypes.AUTOMATIC
  }

  get isManual(): boolean {
    return this.type === TriggerEntityTypes.MANUAL
  }

  get isDate(): boolean {
    return Condition
      .query()
      .where('triggerId', this.id)
      .where('type', ConditionEntityTypes.DATE)
      .exists()
  }

  get isTime(): boolean {
    return Condition
      .query()
      .where('triggerId', this.id)
      .where('type', ConditionEntityTypes.TIME)
      .exists()
  }

  static async get(id: string): Promise<boolean> {
    return await Trigger.dispatch('get', {
      id,
    })
  }

  static async fetch(): Promise<boolean> {
    return await Trigger.dispatch('fetch')
  }

  static async add(data: CreateAutomaticTriggerInterface | CreateManualTriggerInterface, id?: string | null, draft = true): Promise<Item<Trigger>> {
    return await Trigger.dispatch('add', {
      id,
      draft,
      data,
    })
  }

  static async edit(trigger: TriggerInterface, data: TriggerUpdateInterface): Promise<Item<Trigger>> {
    return await Trigger.dispatch('edit', {
      trigger,
      data,
    })
  }

  static async save(trigger: TriggerInterface): Promise<Item<Trigger>> {
    return await Trigger.dispatch('save', {
      trigger,
    })
  }

  static async remove(trigger: TriggerInterface): Promise<boolean> {
    return await Trigger.dispatch('remove', {
      trigger,
    })
  }

  static transmitCommand(trigger: TriggerInterface, command: TriggerControlAction): Promise<boolean> {
    return Trigger.dispatch('transmitCommand', {
      trigger,
      command,
    })
  }

  static reset(): void {
    Trigger.dispatch('reset')
  }
}
