import { ContactType } from "./ContactType";

export type NotificationsStatusType = 'IDLE' |'QUEUED' | 'SUCCESS' | 'ERROR' | 'CANCELED'

export type NotificationsType = {
  id: number;
  scheduled_for: string;
  status: NotificationsStatusType;
  contact: ContactType
}