"use client"

import { motion } from "framer-motion"
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"
import { Badge } from "@/components/ui/badge"
import { Bus, MapPin, Clock, Users, CreditCard, Search, Calendar } from "lucide-react"

const busServices = [
  { title: "Get Available Trip", description: "Search for available bus trips", icon: Search },
  { title: "Get Current Trip Details", description: "View current trip information", icon: Bus },
  { title: "Get Boarding Point Details", description: "Find boarding point locations", icon: MapPin },
  { title: "Reserve Tickets", description: "Reserve seats for later booking", icon: Clock },
  { title: "Book Tickets", description: "Complete ticket booking", icon: CreditCard },
  { title: "Check Booked Tickets", description: "View your booked tickets", icon: Users },
  { title: "Ticket Cancellation", description: "Cancel existing bookings", icon: Calendar },
]

const recentBookings = [
  { id: "BUS001", route: "New York - Boston", date: "2024-01-20", seats: "A1, A2", status: "Confirmed", amount: "$45" },
  { id: "BUS002", route: "Chicago - Detroit", date: "2024-01-18", seats: "B3", status: "Cancelled", amount: "$32" },
  {
    id: "BUS003",
    route: "LA - San Francisco",
    date: "2024-01-15",
    seats: "C1, C2",
    status: "Completed",
    amount: "$68",
  },
]

export function BusBookingContent() {
  return (
    <motion.div
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.5 }}
      className="space-y-6"
    >
      <div>
        <h1 className="text-3xl font-bold text-foreground mb-2">Bus Booking Management</h1>
        <p className="text-muted-foreground">Manage bus bookings, reservations, and trip details</p>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        {busServices.map((service, index) => (
          <motion.div
            key={service.title}
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: index * 0.1 }}
          >
            <Card className="hover:shadow-lg transition-shadow cursor-pointer h-full">
              <CardHeader className="text-center pb-2">
                <service.icon className="w-8 h-8 text-primary mx-auto mb-2" />
                <CardTitle className="text-sm">{service.title}</CardTitle>
              </CardHeader>
              <CardContent className="pt-0">
                <CardDescription className="text-xs text-center">{service.description}</CardDescription>
              </CardContent>
            </Card>
          </motion.div>
        ))}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <Card>
          <CardHeader>
            <CardTitle>Search & Book Tickets</CardTitle>
            <CardDescription>Find and book bus tickets</CardDescription>
          </CardHeader>
          <CardContent>
            <Tabs defaultValue="search" className="w-full">
              <TabsList className="grid w-full grid-cols-2">
                <TabsTrigger value="search">Search Trips</TabsTrigger>
                <TabsTrigger value="book">Book Tickets</TabsTrigger>
              </TabsList>
              <TabsContent value="search" className="space-y-4">
                <div className="grid grid-cols-2 gap-4">
                  <div className="space-y-2">
                    <Label htmlFor="from">From</Label>
                    <Input id="from" placeholder="Departure city" />
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="to">To</Label>
                    <Input id="to" placeholder="Destination city" />
                  </div>
                </div>
                <div className="grid grid-cols-2 gap-4">
                  <div className="space-y-2">
                    <Label htmlFor="date">Travel Date</Label>
                    <Input id="date" type="date" />
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="passengers">Passengers</Label>
                    <Input id="passengers" type="number" placeholder="1" min="1" max="6" />
                  </div>
                </div>
                <Button className="w-full">Search Buses</Button>
              </TabsContent>
              <TabsContent value="book" className="space-y-4">
                <div className="space-y-2">
                  <Label htmlFor="trip-id">Trip ID</Label>
                  <Input id="trip-id" placeholder="Enter trip ID" />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="seats">Select Seats</Label>
                  <Input id="seats" placeholder="e.g., A1, A2, B3" />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="passenger-name">Passenger Name</Label>
                  <Input id="passenger-name" placeholder="Enter passenger name" />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="contact">Contact Number</Label>
                  <Input id="contact" placeholder="Enter contact number" />
                </div>
                <Button className="w-full">Book Tickets</Button>
              </TabsContent>
            </Tabs>
          </CardContent>
        </Card>

        <Card>
          <CardHeader>
            <CardTitle>Recent Bookings</CardTitle>
            <CardDescription>Your recent bus bookings</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-4">
              {recentBookings.map((booking) => (
                <div key={booking.id} className="p-4 border rounded-lg space-y-2">
                  <div className="flex items-center justify-between">
                    <span className="font-medium">{booking.id}</span>
                    <Badge
                      variant={
                        booking.status === "Confirmed"
                          ? "default"
                          : booking.status === "Completed"
                            ? "secondary"
                            : "destructive"
                      }
                    >
                      {booking.status}
                    </Badge>
                  </div>
                  <div className="text-sm text-muted-foreground">
                    <p>{booking.route}</p>
                    <p>
                      Date: {booking.date} • Seats: {booking.seats}
                    </p>
                    <p className="font-medium text-foreground">Amount: {booking.amount}</p>
                  </div>
                  <div className="flex gap-2">
                    <Button size="sm" variant="outline" className="flex-1 bg-transparent">
                      View Details
                    </Button>
                    {booking.status === "Confirmed" && (
                      <Button size="sm" variant="destructive" className="flex-1">
                        Cancel
                      </Button>
                    )}
                  </div>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>
      </div>
    </motion.div>
  )
}
