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
import { CalendarIcon, PaperPlaneIcon, Pencil1Icon } from "@radix-ui/react-icons";
import { Popover, PopoverContent, PopoverTrigger } from "@/components/ui/popover";
import { Calendar } from "@/components/ui/calendar";
import { useState } from "react";
import { cn } from "@/lib/utils";
import { apiFetch } from "@/service/api";

type NotificationEditDialogProps = {
  notification: NotificationsType
  onEdit: () => Promise<void>
}

const NotificationEditDialog = ({ notification, onEdit }: NotificationEditDialogProps) => {
  const [date, setDate] = useState<Date>()
  const [error, setError] = useState('')

  async function handleSubmit() {
    try {
      if (date) {
        const formattedDate = format(date, 'yyyy-MM-dd')
  
        await apiFetch({
          resource: `/notifications/${notification.id}/update-date`,
          method: 'PUT',
          body: { "scheduled_for": formattedDate }
        })

        onEdit()
      }
    } catch (err) {
      if (err instanceof Error) {
        setError(err.message)
      }
    }
  }

  return (
    <Dialog>
      <DialogTrigger>
        <Button variant="outline" size="sm" className="flex items-center gap-2">
          <Pencil1Icon />
          <span className="sr-only lg:not-sr-only">Edit</span>
        </Button>
      </DialogTrigger>
      <DialogContent>
      <DialogHeader>
        <DialogTitle>Edit notification</DialogTitle>
        <DialogDescription>
          Make changes on the date that the notification is scheduled for.
        </DialogDescription>
      </DialogHeader>
      <div className="grid gap-4 py-4">
          <div className="grid grid-cols-4 items-center gap-4">
            <Label htmlFor="name" className="text-right">
              Date
            </Label>
            {/* <Input id="name" value="Pedro Duarte" className="col-span-3" /> */}
            <Popover>
              <PopoverTrigger asChild>
                <Button
                  variant={"outline"}
                  className={cn(
                    "w-[280px] justify-start text-left font-normal",
                    !date && "text-muted-foreground"
                  )}
                >
                  <CalendarIcon className="mr-2 h-4 w-4" />
                  {date ? format(date, "PPP") : <span>Pick a date</span>}
                </Button>
              </PopoverTrigger>
              <PopoverContent className="w-auto p-0">
                <Calendar
                  mode="single"
                  selected={date}
                  onSelect={setDate}
                  initialFocus
                />
              </PopoverContent>
            </Popover>
          </div>
          {/* <div className="grid grid-cols-4 items-center gap-4">
            <Label htmlFor="username" className="text-right">
              Username
            </Label>
            <Input id="username" value="@peduarte" className="col-span-3" />
          </div> */}
        </div>
      <DialogFooter>
        <DialogClose asChild>
          <Button variant="ghost">Cancel</Button>
        </DialogClose>

        <Button type="submit" className="group" onClick={handleSubmit}>
          Confirm
          <PaperPlaneIcon className="ml-2 group-hover:text-secondary transition-colors" />
        </Button>
      </DialogFooter>
    </DialogContent>
    </Dialog>
  );
};

export { NotificationEditDialog };