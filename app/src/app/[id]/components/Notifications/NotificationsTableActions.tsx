import { Button } from "@/components/ui/button";
import { Cross1Icon, ResetIcon, TrashIcon } from "@radix-ui/react-icons";
import { NotificationEditDialog as EditAction } from "./NotificationEditDialog";
import { NotificationsType } from "@/types/NotificationsType";
import { NotificationCancelDialog } from "./NotificationCancelDialog";

type NotificationsTableActionsProps = {
  notification: NotificationsType
  onRetry: () => Promise<void>
  onEdit: () => Promise<void>
}

const NotificationsTableActions = ({ notification, onRetry, onEdit }: NotificationsTableActionsProps) => {
  return (
    <div className="flex gap-2 justify-end cursor-default">
      <EditAction notification={notification} onEdit={onEdit} />
      <Button
      	variant="outline"
      	size="sm"
      	className="flex items-center gap-2"
      	onClick={onRetry}
        disabled={notification.status === 'CANCELED'}
      >
        <ResetIcon />
        <span className="sr-only lg:not-sr-only">Retry</span>
      </Button>
      <NotificationCancelDialog notification={notification} onEdit={onEdit} />
    </div>
  );
};

export { NotificationsTableActions };