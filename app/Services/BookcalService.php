<?php

namespace App\Services;

use App\Models\Bookcal;
use App\Models\BookcalAvailability;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BookcalService
{
    /**
     * Get paginated list of calendars
     *
     * @param array $search Search parameters
     * @param string $sortfield Field to sort by
     * @param string $sortorder Sort order (ASC/DESC)
     * @param int $limit Number of records per page
     * @param int $offset Starting offset
     * @param int $entity Entity ID
     * @return array Array containing calendars and total count
     */
    public function getCalendars(
        array $search = [],
        string $sortfield = 'label',
        string $sortorder = 'ASC',
        int $limit = 25,
        int $offset = 0,
        int $entity = 1
    ): array {
        $query = Bookcal::query()
            ->where('entity', $entity);

        // Apply search filters
        if (!empty($search['ref'])) {
            $query->where('ref', 'like', '%' . $search['ref'] . '%');
        }

        if (!empty($search['label'])) {
            $query->where('label', 'like', '%' . $search['label'] . '%');
        }

        if (isset($search['status'])) {
            $query->where('status', $search['status']);
        }

        // Get total count
        $total = $query->count();

        // Apply sorting and pagination
        $query->orderBy($sortfield, $sortorder)
              ->skip($offset)
              ->take($limit);

        $calendars = $query->get();

        return [
            'calendars' => $calendars,
            'total' => $total,
        ];
    }

    /**
     * Get a single calendar by ID
     *
     * @param int $id Calendar ID
     * @return Bookcal|null
     */
    public function getById(int $id): ?Bookcal
    {
        return Bookcal::find($id);
    }

    /**
     * Get calendar by reference
     *
     * @param string $ref Calendar reference
     * @return Bookcal|null
     */
    public function getByRef(string $ref): ?Bookcal
    {
        return Bookcal::where('ref', $ref)->first();
    }

    /**
     * Get availabilities for a calendar
     *
     * @param int $calendarId Calendar ID
     * @return Collection
     */
    public function getAvailabilities(int $calendarId): Collection
    {
        return BookcalAvailability::where('fk_bookcal', $calendarId)
            ->orderBy('start_date', 'ASC')
            ->get();
    }

    /**
     * Get available slots for a date range
     *
     * @param int $calendarId Calendar ID
     * @param string $startDate Start date (YYYY-MM-DD)
     * @param string $endDate End date (YYYY-MM-DD)
     * @return Collection
     */
    public function getAvailableSlots(int $calendarId, string $startDate, string $endDate): Collection
    {
        return BookcalAvailability::where('fk_bookcal', $calendarId)
            ->where('status', 1) // Available status
            ->whereBetween('start_date', [$startDate, $endDate])
            ->orderBy('start_date', 'ASC')
            ->get();
    }

    /**
     * Create a new calendar
     *
     * @param array $data Calendar data
     * @return Bookcal
     */
    public function createCalendar(array $data): Bookcal
    {
        return Bookcal::create($data);
    }

    /**
     * Update an existing calendar
     *
     * @param int $id Calendar ID
     * @param array $data Updated data
     * @return bool
     */
    public function updateCalendar(int $id, array $data): bool
    {
        $calendar = Bookcal::findOrFail($id);
        return $calendar->update($data);
    }

    /**
     * Delete a calendar
     *
     * @param int $id Calendar ID
     * @return bool
     */
    public function deleteCalendar(int $id): bool
    {
        $calendar = Bookcal::findOrFail($id);
        return $calendar->delete();
    }

    /**
     * Create an availability slot
     *
     * @param array $data Availability data
     * @return BookcalAvailability
     */
    public function createAvailability(array $data): BookcalAvailability
    {
        return BookcalAvailability::create($data);
    }

    /**
     * Update an availability slot
     *
     * @param int $id Availability ID
     * @param array $data Updated data
     * @return bool
     */
    public function updateAvailability(int $id, array $data): bool
    {
        $availability = BookcalAvailability::findOrFail($id);
        return $availability->update($data);
    }

    /**
     * Delete an availability slot
     *
     * @param int $id Availability ID
     * @return bool
     */
    public function deleteAvailability(int $id): bool
    {
        $availability = BookcalAvailability::findOrFail($id);
        return $availability->delete();
    }

    /**
     * Book a slot
     *
     * @param int $availabilityId Availability ID
     * @param array $bookingData Booking data (user, contact info, etc.)
     * @return bool
     */
    public function bookSlot(int $availabilityId, array $bookingData): bool
    {
        $availability = BookcalAvailability::findOrFail($availabilityId);
        
        // Update availability status to booked
        $availability->update([
            'status' => 2, // Booked status
            'fk_user_book' => $bookingData['user_id'] ?? null,
        ]);

        return true;
    }

    /**
     * Cancel a booking
     *
     * @param int $availabilityId Availability ID
     * @return bool
     */
    public function cancelBooking(int $availabilityId): bool
    {
        $availability = BookcalAvailability::findOrFail($availabilityId);
        
        // Reset availability status
        $availability->update([
            'status' => 1, // Available status
            'fk_user_book' => null,
        ]);

        return true;
    }
}
