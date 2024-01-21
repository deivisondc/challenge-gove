'use client'

import Link from "next/link"

import { cn } from "@/lib/utils"
import { usePathname } from "next/navigation";

export function MainNav({
  className,
  ...props
}: React.HTMLAttributes<HTMLElement>) {
  const pathName = usePathname();

  const navLinks = [
    { href: '/', label: 'Overview' },
    { href: '/files', label: 'Files' },
    { href: '/notifications', label: 'Notifications' },
  ]
  
  return (
    <nav
      className={cn("flex items-center space-x-4 lg:space-x-6", className)}
      {...props}
    >
      {navLinks.map((navLink) => (
        <Link
          key={navLink.href}
          href={navLink.href}
          className={cn("text-sm font-medium text-muted-foreground transition-colors hover:text-primary", {
            'text-foreground': pathName === navLink.href
          })}
        >
          {navLink.label}
        </Link>
      ))}
    </nav>
  )
}