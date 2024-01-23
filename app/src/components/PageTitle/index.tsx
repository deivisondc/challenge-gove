'use client'

import { useRouter } from "next/navigation";
import { ReactNode } from "react";

interface PageTitleProps {
  children: ReactNode
  backButtonHref?: string
}

const PageTitle = ({ backButtonHref, children }: PageTitleProps) => {
  const { push } = useRouter()

  function onBackButtonClick() {
    if (backButtonHref) {
      push(backButtonHref)
    }
  }

  return (
    <div className="flex items-center gap-2">
      {backButtonHref && (
        <h2
          className="text-3xl font-bold text-primary cursor-pointer hover:text-secondary transition-colors"
          onClick={onBackButtonClick}
        >
          &laquo;
        </h2>
      )}
      <h2 className="text-3xl font-bold text-primary">{children}</h2>
    </div>
  );
};

export { PageTitle };