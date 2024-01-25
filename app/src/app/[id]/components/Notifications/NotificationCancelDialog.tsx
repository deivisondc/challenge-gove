'use client'

import { Button } from "@/components/ui/button";
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog"
import { NotificationsType } from "@/types/NotificationsType";
import { Cross1Icon} from "@radix-ui/react-icons";
import { useState } from "react";
import { apiFetch } from "@/service/api";

type NotificationCancelDialogProps = {
  notification: NotificationsType
  onEdit: () => Promise<void>
}

const NotificationCancelDialog = ({ notification, onEdit }: NotificationCancelDialogProps) => {
  const [error, setError] = useState('');

  async function handleSubmit() {
    try {
      await apiFetch({
        resource: `/notifications/${notification.id}/cancel`,
        method: 'PUT',
      })

      onEdit()
    } catch (err) {
      if (err instanceof Error) {
        setError(err.message)
      }
    }
  }

  return (
    <Dialog>
      <DialogTrigger asChild>
        <Button
          variant="destructive"
          size="sm"
          className="flex items-center gap-2"
          disabled={['SUCCESS', 'ERROR', 'CANCELED'].includes(notification.status)}
        >
          <Cross1Icon />
          <span className="sr-only lg:not-sr-only">Cancel</span>
        </Button>
      </DialogTrigger>
      <DialogContent>
      <DialogHeader>
        <DialogTitle>Confirmation</DialogTitle>
        <DialogDescription>
          Are you sure you want to cancel this notification to be sent on the scheduled date?
        </DialogDescription>
      </DialogHeader>
      <DialogFooter>
        <DialogClose asChild>
          <Button variant="ghost">No</Button>
        </DialogClose>
        
        <Button type="submit" className="group" onClick={handleSubmit}>
          Yes
        </Button>
      </DialogFooter>
    </DialogContent>
    </Dialog>
  );
};

export { NotificationCancelDialog };