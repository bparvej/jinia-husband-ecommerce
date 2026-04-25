const userService = require('./user.service');
const { getPageNumbers } = require('../../utils/pagination');

class UserController {
  async adminIndex(req, res) {
    try {
      const page = parseInt(req.query.page) || 1;
      const { search, role_id } = req.query;
      const { users, pagination } = await userService.getUsers(page, 12, { search, role_id });
      const roles = await userService.getRoles();

      const viewData = {
        layout: 'layouts/admin',
        title: 'User Management — HomeI Admin',
        users,
        roles,
        pagination,
        pageNumbers: getPageNumbers(pagination.page, pagination.totalPages),
        filters: { search, role_id },
      };

      if (req.headers['hx-request'] && req.query._partial) {
        return res.render('admin/users/partials/user-table', viewData);
      }

      res.render('admin/users/index', viewData);
    } catch (err) {
      res.status(500).render('pages/error', {
        layout: 'layouts/admin',
        title: 'Error',
        message: err.message,
        error: err,
      });
    }
  }

  async store(req, res) {
    try {
      await userService.createUser(req.body);
      if (req.headers['hx-request']) {
        return res.send('<span class="toast toast-success">User created successfully!</span>');
      }
      res.redirect('/admin/users');
    } catch (err) {
      if (req.headers['hx-request']) {
        return res.status(400).send(`<span class="toast toast-error">${err.message}</span>`);
      }
      res.status(400).json({ error: err.message });
    }
  }

  async update(req, res) {
    try {
      await userService.updateUser(req.params.id, req.body);
      if (req.headers['hx-request']) {
        return res.send('<span class="toast toast-success">User updated successfully!</span>');
      }
      res.redirect('/admin/users');
    } catch (err) {
      if (req.headers['hx-request']) {
        return res.status(400).send(`<span class="toast toast-error">${err.message}</span>`);
      }
      res.status(400).json({ error: err.message });
    }
  }

  async delete(req, res) {
    try {
      await userService.deleteUser(req.params.id);
      if (req.headers['hx-request']) {
        return res.send(''); // Return empty to remove row or trigger refresh
      }
      res.redirect('/admin/users');
    } catch (err) {
      if (req.headers['hx-request']) {
        return res.status(400).send(`<span class="toast toast-error">${err.message}</span>`);
      }
      res.status(400).json({ error: err.message });
    }
  }
}

module.exports = new UserController();
