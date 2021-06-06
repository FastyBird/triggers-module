import { ModelPropertiesMapper } from 'jsona'
import {
  IModelPropertiesMapper,
  TJsonaModel,
  TJsonaRelationships,
} from 'jsona/lib/JsonaTypes'
import { format as dateFormat } from 'date-fns'

import Trigger from '@/lib/models/triggers/Trigger'
import { TriggerEntityTypes } from '@/lib/models/triggers/types'
import { ConditionEntityTypes } from '@/lib/models/conditions/types'
import { RelationInterface } from '@/lib/types'

const RELATIONSHIP_NAMES_PROP = 'relationshipNames'

export class JsonApiModelPropertiesMapper extends ModelPropertiesMapper implements IModelPropertiesMapper {
  getAttributes(model: TJsonaModel): { [index: string]: any } {
    const exceptProps = ['id', '$id', 'type', 'draft', RELATIONSHIP_NAMES_PROP]

    if (
      model.type !== TriggerEntityTypes.AUTOMATIC &&
      model.type !== TriggerEntityTypes.MANUAL
    ) {
      exceptProps.push('triggerId')
    } else if (
      model.type === ConditionEntityTypes.DATE ||
      model.type === ConditionEntityTypes.TIME
    ) {
      exceptProps.push('channel')
      exceptProps.push('device')
      exceptProps.push('property')
      exceptProps.push('operator')
      exceptProps.push('operand')
    }

    if (Array.isArray(model[RELATIONSHIP_NAMES_PROP])) {
      exceptProps.push(...model[RELATIONSHIP_NAMES_PROP])
    }

    const attributes: { [index: string]: any } = {}

    Object.keys(model)
      .forEach((attrName) => {
        if (!exceptProps.includes(attrName)) {
          const kebabName = attrName.replace(/([a-z][A-Z0-9])/g, g => `${g[0]}_${g[1].toLowerCase()}`)

          let jsonAttributes = model[attrName]

          if (attrName === 'days') {
            jsonAttributes = Object.values(jsonAttributes)
          } else if (attrName === 'time' || attrName === 'date') {
            if (jsonAttributes !== null) {
              if (typeof jsonAttributes === 'string') {
                jsonAttributes = dateFormat(new Date(jsonAttributes), 'yyyy-MM-dd\'T\'HH:mm:ssXXXXX')
              } else {
                jsonAttributes = dateFormat(jsonAttributes, 'yyyy-MM-dd\'T\'HH:mm:ssXXXXX')
              }
            }
          } else if (typeof jsonAttributes === 'object' && jsonAttributes !== null) {
            jsonAttributes = {}

            Object.keys(model[attrName]).forEach((subAttrName) => {
              const kebabSubName = subAttrName.replace(/([a-z][A-Z0-9])/g, g => `${g[0]}_${g[1].toLowerCase()}`)

              Object.assign(jsonAttributes, {[kebabSubName]: model[attrName][subAttrName]})
            })
          }

          attributes[kebabName] = jsonAttributes
        }
      })

    return attributes
  }

  getRelationships(model: TJsonaModel): TJsonaRelationships {
    if (
      !Object.prototype.hasOwnProperty.call(model, RELATIONSHIP_NAMES_PROP) ||
      !Array.isArray(model[RELATIONSHIP_NAMES_PROP])
    ) {
      return {}
    }

    const relationshipNames = model[RELATIONSHIP_NAMES_PROP]

    const relationships: { [index: string]: RelationInterface | RelationInterface[] } = {}

    relationshipNames
      .forEach((relationName: string) => {
        const kebabName = relationName.replace(/([a-z][A-Z0-9])/g, g => `${g[0]}_${g[1].toLowerCase()}`)

        if (model[relationName] !== undefined) {
          if (Array.isArray(model[relationName])) {
            relationships[kebabName] = model[relationName]
              .map((item: TJsonaModel) => {
                return {
                  id: item.id,
                  type: item.type,
                }
              })
          } else if (typeof model[relationName] === 'object' && model[relationName] !== null) {
            relationships[kebabName] = {
              id: model[relationName].id,
              type: model[relationName].type,
            }
          }
        }
      })

    if (Object.prototype.hasOwnProperty.call(model, 'triggerId')) {
      const trigger = Trigger.find(model.triggerId)

      if (trigger !== null) {
        relationships.trigger = {
          id: trigger.id,
          type: trigger.type,
        }
      }
    }

    return relationships
  }
}

export default JsonApiModelPropertiesMapper
