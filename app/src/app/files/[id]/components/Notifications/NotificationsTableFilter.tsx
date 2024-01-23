'use client'

import { Button } from "@/components/ui/button";
import { CalendarIcon, MixerHorizontalIcon } from "@radix-ui/react-icons";
import { Dispatch, SetStateAction, useState } from "react";
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from "@/components/ui/popover"
import { Calendar } from "@/components/ui/calendar";
import { cn } from "@/lib/utils";
import { format } from "date-fns";
import { PopoverClose } from "@radix-ui/react-popover";
import { NotificationsStatusType } from "@/types/NotificationsType";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select"

type FilterTypes = 'name' | 'contact' | 'status' | 'scheduled_for'
type SelectedFilters = Partial<Record<FilterTypes, string>>

type NotificationsTableFilterProps = {
  setFilter: Dispatch<SetStateAction<string>>
}

const NotificationsTableFilter = ({ setFilter }: NotificationsTableFilterProps) => {
  const [filterCounter, setFilterCounter] = useState(0);
  const [name, setName] = useState('');
  const [contact, setContact] = useState('');
  const [status, setStatus] = useState<string>();
  const [date, setDate] = useState<Date>()

  const notificationStatus: Array<NotificationsStatusType> = ['IDLE', 'QUEUED', 'SUCCESS', 'ERROR', 'CANCELED']

  function handleClearButton() {
    setFilterCounter(0)

    setName('')
    setContact('')
    setStatus('')
    setDate(undefined)

    setFilter('')
  }

  function handleApplyFilterButton() {
    let queryParam = ''
    let counter = 0;

    if (name) {
      queryParam += `&name=${name}`
      counter++
    }
    if (contact) {
      queryParam += `&contact=${contact}`
      counter++
    }
    if (status) {
      queryParam += `&status=${status}`
      counter++
    }
    if (date) {
      queryParam += `&scheduledFor=${format(date, 'yyyy-MM-dd')}`
      counter++
    }

    setFilter(queryParam)
    setFilterCounter(counter)
  }

  return (
    <div className="flex gap-4 items-baseline">
      <Popover>
        <PopoverTrigger asChild>
          <Button className="flex gap-2 items-center mb-2 group" size="sm">
            <MixerHorizontalIcon className="group-hover:text-secondary transition-colors" />
            <span>
              Filter
            </span>
          </Button>
        </PopoverTrigger>
        <PopoverContent className="w-[400px] mx-5">
          <div className="grid gap-4">
            <div className="space-y-2">
              <p className="text-sm text-muted-foreground">
                Fill the inputs you want to filter
              </p>
            </div>
            <div className="grid gap-2">
              <div className="grid grid-cols-3 items-center gap-4">
                <Label htmlFor="width">Name</Label>
                <Input 
                  id="width"
                  className="col-span-2 h-8"
                  value={name}
                  onChange={(e) => setName(e.target.value)}
                />
              </div>
              <div className="grid grid-cols-3 items-center gap-4">
                <Label htmlFor="maxWidth">Contact</Label>
                <Input
                  id="maxWidth"
                  className="col-span-2 h-8"
                  value={contact}
                  onChange={(e) => setContact(e.target.value)}
                />
              </div>
              <div className="grid grid-cols-3 items-center gap-4">
                <Label htmlFor="height">Status</Label>
                <Select value={status} onValueChange={setStatus}>
                  <SelectTrigger className="w-[180px]">
                    <SelectValue placeholder="Status" />
                  </SelectTrigger>
                  <SelectContent>
                    {notificationStatus.map(option => (
                      <SelectItem key={option} value={option}>
                        {`${option.slice(0, 1)}${option.slice(1).toLowerCase()}`}
                      </SelectItem>
                    ))}
                  </SelectContent>
                </Select>
              </div>
              <div className="grid grid-cols-3 items-center gap-4">
                <Label htmlFor="maxHeight">Scheduled for</Label>
                <Popover>
                  <PopoverTrigger asChild>
                    <Button
                      variant={"outline"}
                      className={cn(
                        "min-w-max justify-start text-left font-normal",
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
            </div>
          </div>

          <div className="mt-4 flex justify-end gap-4">
            <PopoverClose asChild>
              <Button variant="ghost" onClick={handleClearButton}>Clear</Button>
            </PopoverClose>
            <PopoverClose asChild>
              <Button className="group flex gap-2" onClick={handleApplyFilterButton}>
                <MixerHorizontalIcon className="group-hover:text-secondary transition-colors" />
                Apply filter
              </Button>
            </PopoverClose>
          </div>
        </PopoverContent>
      </Popover>

      <div className="flex gap-2">
        {filterCounter === 0 ? (
          <span className="text-xs border border-primary text-primary px-4 py-1 rounded-full">No filters selected</span>
        ) : (
          <span className="text-xs border border-primary text-primary px-4 py-1 rounded-full">
            {filterCounter === 1 ? '1 filter' : `${filterCounter} filters`} selected
          </span>
        )}
      </div>
    </div>
  );
};

export { NotificationsTableFilter };