'use client'

import { Button } from "@/components/ui/button";
import { format } from "date-fns"
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
import { Label } from "@/components/ui/label";
import { NotificationsType } from "@/types/NotificationsType";
import { CalendarIcon, Cross1Icon, PaperPlaneIcon, Pencil1Icon } from "@radix-ui/react-icons";
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover";
import { Calendar } from "@/components/ui/calendar";
import { useState } from "react";
import { cn } from "@/lib/utils";

type NotificationCancelDialogProps = {
  notification: NotificationsType
  onEdit: () => Promise<void>
}

const NotificationCancelDialog = ({ notification, onEdit }: NotificationCancelDialogProps) => {

  function handleSubmit() {
    fetch(`http://localhost:8000/api/notifications/${notification.id}/cancel`, {
      method: 'PUT',
    }).finally(() => {
      onEdit()
    })
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