'use client'

import Link from "next/link"

import { cn } from "@/lib/utils"
import { usePathname } from "next/navigation";

type NavLinkType = {
  href: string,
  label: string,
  exact?: boolean
}

export function MainNav({
  className,
  ...props
}: React.HTMLAttributes<HTMLElement>) {
  const pathName = usePathname();

  const navLinks: Array<NavLinkType> = [
    { href: '/', label: 'Files' },
  ]

  const isActiveLink = (navLink: NavLinkType) => {
    if (navLink.exact) {
      return pathName === navLink.href
    }

    return pathName.startsWith(navLink.href)
  }
  
  return (
    <nav
      className={cn("flex items-center space-x-4 lg:space-x-6", className)}
      {...props}
    >
      {navLinks.map((navLink) => (
        <Link
          key={navLink.href}
          href={navLink.href}
          className={cn("text-sm font-medium text-primary-muted transition-colors hover:text-primary-foreground", {
            'text-secondary hover:text-secondary': isActiveLink(navLink),
          })}
        >
          {navLink.label}
        </Link>
      ))}
    </nav>
  )
}