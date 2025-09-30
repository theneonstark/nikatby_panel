"use client"

import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { ChartContainer, ChartTooltip, ChartTooltipContent } from "@/components/ui/chart"
import { PieChart, Pie, Cell, ResponsiveContainer } from "recharts"

const data = [
  { name: "Direct", value: 29, color: "hsl(var(--chart-1))" },
  { name: "Social", value: 22, color: "hsl(var(--chart-2))" },
  { name: "Email", value: 15, color: "hsl(var(--chart-3))" },
  { name: "Referral", value: 20, color: "hsl(var(--chart-4))" },
  { name: "Organic", value: 14, color: "hsl(var(--chart-5))" },
]

export function TrafficSources() {
  return (
    <Card>
      <CardHeader>
        <CardTitle>Traffic Sources</CardTitle>
        <CardDescription>Website traffic by source</CardDescription>
      </CardHeader>
      <CardContent>
        <ChartContainer
          config={{
            direct: {
              label: "Direct",
              color: "hsl(var(--chart-1))",
            },
            social: {
              label: "Social",
              color: "hsl(var(--chart-2))",
            },
            email: {
              label: "Email",
              color: "hsl(var(--chart-3))",
            },
            referral: {
              label: "Referral",
              color: "hsl(var(--chart-4))",
            },
            organic: {
              label: "Organic",
              color: "hsl(var(--chart-5))",
            },
          }}
          className="h-[300px]"
        >
          <ResponsiveContainer width="100%" height="100%">
            <PieChart>
              <Pie
                data={data}
                cx="50%"
                cy="50%"
                outerRadius={80}
                dataKey="value"
                label={({ name, value }) => `${name}: ${value}%`}
              >
                {data.map((entry, index) => (
                  <Cell key={`cell-${index}`} fill={entry.color} />
                ))}
              </Pie>
              <ChartTooltip content={<ChartTooltipContent />} />
            </PieChart>
          </ResponsiveContainer>
        </ChartContainer>
      </CardContent>
    </Card>
  )
}
